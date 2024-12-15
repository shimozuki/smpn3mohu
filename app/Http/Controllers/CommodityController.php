<?php

namespace App\Http\Controllers;

use App\Commodity;
use App\CommodityLocation;
use App\SchoolOperationalAssistance;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CommoditiesExport;
use App\Http\Requests\CommodityImportRequest;
use App\Http\Requests\StoreCommodityRequest;
use App\Http\Requests\UpdateCommodityRequest;
use App\Imports\CommoditiesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\CommodityLoan;

class CommodityController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Commodity::class, 'commodity');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Commodity::query();
        $query->when(request()->filled('condition'), function ($q) {
            return $q->where('condition', request('condition'));
        });

        $query->when(request()->filled('commodity_location_id'), function ($q) {
            return $q->where('commodity_location_id', request('commodity_location_id'));
        });

        $query->when(request()->filled('school_operational_assistance_id'), function ($q) {
            return $q->where('school_operational_assistance_id', request('school_operational_assistance_id'));
        });

        $query->when(request()->filled('year_of_purchase'), function ($q) {
            return $q->where('year_of_purchase', request('year_of_purchase'));
        });

        $query->when(request()->filled('material'), function ($q) {
            return $q->where('material', request('material'));
        });

        $query->when(request()->filled('brand'), function ($q) {
            return $q->where('brand', request('brand'));
        });

        $commodities = $query->latest()->get();
        $year_of_purchases = Commodity::pluck('year_of_purchase')->unique()->sort();
        $commodity_brands = Commodity::pluck('brand')->unique()->sort();
        $commodity_materials = Commodity::pluck('material')->unique()->sort();
        $school_operational_assistances = SchoolOperationalAssistance::orderBy('name', 'ASC')->get();
        $commodity_locations = CommodityLocation::orderBy('name', 'ASC')->get();

        $commodity_loans = CommodityLoan::with('commodity')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view(
            'commodities.index',
            compact(
                'commodities',
                'school_operational_assistances',
                'commodity_locations',
                'year_of_purchases',
                'commodity_brands',
                'commodity_materials',
                'commodity_loans'
            )
        );
    }

    public function searchByCode($code)
    {
        try {
            $commodity = Commodity::with(['commodity_location'])
                ->where('item_code', $code)
                ->first();

            if ($commodity) {
                return response()->json([
                    'success' => true,
                    'commodity' => $commodity
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    public function getQrCode(Commodity $commodity)
    {
        try {
            // Generate QR code dengan format SVG
            $qrcode = QrCode::size(200)
                ->format('svg')
                ->style('square')
                ->eye('square')
                ->color(0, 0, 0)
                ->backgroundColor(255, 255, 255)
                ->margin(1)
                ->generate($commodity->item_code);

            // Pastikan QR code dikirim sebagai string SVG
            return response()->json([
                'success' => true,
                'qrcode' => strval($qrcode),  // Konversi ke string
                'name' => $commodity->name,
                'code' => $commodity->item_code
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommodityRequest $request)
    {
        Commodity::create($request->validated());

        return to_route('barang.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommodityRequest $request, Commodity $commodity)
    {
        $commodity->update($request->all());

        return to_route('barang.index')->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commodity $commodity)
    {
        $commodity->delete();

        return to_route('barang.index')->with('success', 'Data berhasil dihapus!');
    }

    /**
     * Generate PDF for all commodities.
     */
    public function generatePDF()
    {
        $this->authorize('print barang');

        $commodities = Commodity::all();
        $sekolah = env('NAMA_SEKOLAH', 'Barang Milik Sekolah');
        $pdf = Pdf::loadView('commodities.pdf', compact(['commodities', 'sekolah']))->setPaper('a4');

        return $pdf->download('print.pdf');
    }

    /**
     * Generate PDF for a specific commodity.
     */
    public function generatePDFIndividually($id)
    {
        $this->authorize('print individual barang');

        $commodity = Commodity::find($id);
        $sekolah = env('NAMA_SEKOLAH', 'Barang Milik Sekolah');
        $pdf = Pdf::loadView('commodities.pdfone', compact(['commodity', 'sekolah']))->setPaper('a4');

        return $pdf->download('print.pdf');
    }

    /**
     * Export commodities data to Excel.
     */
    public function export()
    {
        $this->authorize('export barang');

        return Excel::download(new CommoditiesExport, 'daftar-barang-' . date('d-m-Y') . '.xlsx');
    }

    /**
     * Import commodities data from Excel.
     */
    public function import(CommodityImportRequest $request)
    {
        $this->authorize('import barang');

        Excel::import(new CommoditiesImport, $request->file('file'));

        return to_route('barang.index')->with('success', 'Data barang berhasil diimpor!');
    }
}
