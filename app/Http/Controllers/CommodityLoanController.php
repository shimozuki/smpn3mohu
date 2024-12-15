<?php

namespace App\Http\Controllers;

use App\CommodityLoan;
use App\Commodity;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CommodityLoanController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'commodity_id' => 'required|exists:commodities,id',
            'purpose' => 'required|string',
            'due_date' => 'required|date|after:today',
        ]);

        try {
            DB::beginTransaction();

            // Buat peminjaman baru
            $loan = CommodityLoan::create([
                'commodity_id' => $request->commodity_id,
                'user_id' => auth()->id(), // ID user yang sedang login
                'loan_date' => Carbon::now(),
                'due_date' => $request->due_date,
                'quantity' => 1,
                'status' => 'pending',
                'purpose' => $request->purpose,
            ]);

            // Update note pada commodity menjadi "Dalam Peminjaman"
            Commodity::where('id', $request->commodity_id)
                    ->update(['note' => 'Menunggu Konfirmasi Peminjaman']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permintaan peminjaman berhasil diajukan',
                'data' => $loan
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function approveLoan($id)
    {
        try {
            DB::beginTransaction();
            
            $loan = CommodityLoan::where('commodity_id', $id)
                                ->where('status', 'pending')
                                ->first();

            if (!$loan) {
                throw new \Exception('Data peminjaman tidak ditemukan');
            }

            $loan->status = 'approved';
            $loan->save();

            $commodity = Commodity::findOrFail($id);
            $commodity->note = 'Di Pinjam';
            $commodity->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil dikonfirmasi'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirmReturn($id)
    {
        try {
            DB::beginTransaction();

            // Update status peminjaman
            $commodityLoan = CommodityLoan::where('commodity_id', $id)
                ->where('status', 'approved')
                ->firstOrFail();
            
            $commodityLoan->update([
                'status' => 'returned',
                'return_date' => now()
            ]);

            // Update note pada barang
            $commodity = Commodity::findOrFail($id);
            $commodity->update([
                'note' => 'Tersedia'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengembalian barang berhasil dikonfirmasi'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}