<x-layout>
    <x-slot name="title">Halaman Daftar Barang</x-slot>
    <x-slot name="page_heading">Daftar Barang</x-slot>

    <div class="card">
        <div class="card-body">
            @php
                $userRole = auth()->user()->roles->pluck('name')->first();
                $allowedRoles = ['Administrator', 'Staff TU'];
            @endphp
            @if (in_array($userRole, $allowedRoles))
                @include('utilities.alert')
                <div class="d-flex justify-content-end mb-3">
                    <div class="btn-group">
                        @can('import barang')
                            <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#excel_menu">
                                <i class="fas fa-fw fa-upload"></i>
                                Import Excel
                            </button>
                        @endcan

                        @can('export barang')
                            <form action="{{ route('barang.export') }}" method="POST">
                                @csrf

                                <button type="submit" class="btn btn-success mr-2">
                                    <i class="fas fa-fw fa-download"></i>
                                    Export Excel
                                </button>
                            </form>
                        @endcan

                        <button type="button" class="btn btn-info mr-2" data-toggle="modal"
                            data-target="#scan_qr_modal">
                            <i class="fas fa-fw fa-qrcode"></i>
                            Scan QR Code
                        </button>

                        @can('tambah barang')
                            <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                data-target="#commodity_create_modal">
                                <i class="fas fa-fw fa-plus"></i>
                                Tambah Data
                            </button>
                        @endcan

                        @can('print barang')
                            <form action="{{ route('barang.print') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-fw fa-print"></i>
                                    Print
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
                <x-filter>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="commodity_location_id">Lokasi Barang:</label>
                                <select name="commodity_location_id" id="commodity_location_id" class="form-control">
                                    <option value="">Pilih lokasi barang..</option>
                                    @foreach ($commodity_locations as $commodity_location)
                                        <option value="{{ $commodity_location->id }}" @selected(request('commodity_location_id') == $commodity_location->id)>
                                            {{ $commodity_location->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="school_operational_assistance_id">Asal Perolehan:</label>
                                <select name="school_operational_assistance_id" id="school_operational_assistance_id"
                                    class="form-control">
                                    <option value="">Pilih asal perolehan..</option>
                                    @foreach ($school_operational_assistances as $school_operational_assistance)
                                        <option value="{{ $school_operational_assistance->id }}"
                                            @selected(request('school_operational_assistance_id') == $school_operational_assistance->id)>{{ $school_operational_assistance->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="condition">Kondisi:</label>
                                <select name="condition" id="condition" class="form-control">
                                    <option value="">Pilih kondisi..</option>
                                    <option value="1" @selected(request('condition') == 1)>Baik</option>
                                    <option value="2" @selected(request('condition') == 2)>Kurang Baik</option>
                                    <option value="3" @selected(request('condition') == 3)>Rusak Berat</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="year_of_purchase">Tahun Pembelian:</label>
                                <select name="year_of_purchase" id="year_of_purchase" class="form-control">
                                    <option value="">Pilih tahun pembelian..</option>
                                    @foreach ($year_of_purchases as $year_of_purchase)
                                        <option value="{{ $year_of_purchase }}" @selected(request('year_of_purchase') == $year_of_purchase)>
                                            {{ $year_of_purchase }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material">Bahan:</label>
                                <select name="material" id="material" class="form-control">
                                    <option value="">Pilih bahan..</option>
                                    @foreach ($commodity_materials as $material)
                                        <option value="{{ $material }}" @selected(request('material') == $material)>
                                            {{ $material }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand">Merk:</label>
                                <select name="brand" id="brand" class="form-control">
                                    <option value="">Pilih merk..</option>
                                    @foreach ($commodity_brands as $brand)
                                        <option value="{{ $brand }}" @selected(request('brand') == $brand)>
                                            {{ $brand }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <x-slot name="resetFilterURL">{{ route('barang.index') }}</x-slot>
                </x-filter>


                <div class="row">
                    <div class="col-lg-12">
                        <x-datatable>
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Kode Barang</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Bahan</th>
                                    <th scope="col">Merk</th>
                                    <th scope="col">Tahun Pembelian</th>
                                    <th scope="col">Kondisi</th>
                                    <th scope="col">Status Peminjaman</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($commodities as $commodity)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $commodity->item_code }}</td>
                                        <td>{{ Str::limit($commodity->name, 55, '...') }}</td>
                                        <td>{{ $commodity->material }}</td>
                                        <td>{{ $commodity->brand }}</td>
                                        <td>{{ $commodity->year_of_purchase }}</td>
                                        @if ($commodity->condition === 1)
                                            <td>
                                                <span class="badge badge-pill badge-success" title="Baik">
                                                    <i class="fas fa-fw fa-check-circle"></i>
                                                    Baik
                                                </span>
                                            </td>
                                        @elseif($commodity->condition === 2)
                                            <td>
                                                <span class="badge badge-pill badge-warning" title="Kurang Baik">
                                                    <i class="fa fa-fw fa-exclamation-circle"></i>
                                                    Kurang Baik
                                                </span>
                                            </td>
                                        @else
                                            <td>
                                                <span class="badge badge-pill badge-danger" title="Rusak Berat">
                                                    <i class="fa fa-fw fa-times-circle"></i>
                                                    Rusak Berat</span>
                                            </td>
                                        @endif
                                        <td><b>{{ $commodity->note }}</b></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                @can('detail barang')
                                                    <a data-id="{{ $commodity->id }}"
                                                        class="btn btn-sm btn-info text-white show-modal mr-2"
                                                        data-toggle="modal" data-target="#show_commodity"
                                                        title="Lihat Detail">
                                                        <i class="fas fa-fw fa-search"></i>
                                                    </a>
                                                @endcan

                                                @can('ubah barang')
                                                    <a data-id="{{ $commodity->id }}"
                                                        class="btn btn-sm btn-success text-white edit-modal mr-2"
                                                        data-toggle="modal" data-target="#edit_commodity"
                                                        title="Ubah data">
                                                        <i class="fas fa-fw fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('print individual barang')
                                                    <form action="{{ route('barang.print-individual', $commodity->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary mr-2">
                                                            <i class="fas fa-fw fa-print"></i>
                                                        </button>
                                                    </form>
                                                @endcan

                                                <a data-id="{{ $commodity->id }}"
                                                    class="btn btn-sm btn-secondary text-white qr-modal mr-2"
                                                    data-toggle="modal" data-target="#qr_code_modal"
                                                    title="Lihat QR Code">
                                                    <i class="fas fa-fw fa-qrcode"></i>
                                                </a>

                                                @if ($commodity->note == 'Menunggu Konfirmasi Peminjaman')
                                                    <a data-id="{{ $commodity->id }}"
                                                        class="btn btn-sm btn-success text-white approve-loan mr-2"
                                                        onclick="approveLoan({{ $commodity->id }})"
                                                        title="Konfirmasi Peminjaman">
                                                        <i class="fas fa-fw fa-check"></i>
                                                    </a>
                                                @endif

												@if ($commodity->note == 'Di Pinjam')
                                                    <a data-id="{{ $commodity->id }}"
                                                        class="btn btn-sm btn-success text-white approve-loan mr-2"
                                                        onclick="confirmReturn({{ $commodity->id }})"
                                                        title="Konfirmasi Pengembalian">
                                                        <i class="fas fa-fw fa-backspace"></i>
                                                    </a>
                                                @endif

                                                @can('hapus barang')
                                                    <form action="{{ route('barang.destroy', $commodity) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-danger delete-button"><i
                                                                class="fas fa-fw fa-trash-alt"></i></button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </x-datatable>
                    </div>

                </div>
            @else
                <center>
                    <button type="button" class="btn btn-info mr-2 mb-4" data-toggle="modal"
                        data-target="#scan_qr_modal">
                        <i class="fas fa-fw fa-qrcode"></i>
                        Scan QR Code
                    </button>
                </center>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Peminjaman Barang</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($commodity_loans as $loan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $loan->commodity->item_code }}</td>
                                            <td>{{ $loan->commodity->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</td>
                                            <td>
                                                @if ($loan->status == 'pending')
                                                    <span class="badge badge-warning">Menunggu</span>
                                                @elseif($loan->status == 'approved')
                                                    <span class="badge badge-info">Disetujui</span>
                                                @elseif($loan->status == 'borrowed')
                                                    <span class="badge badge-primary">Dipinjam</span>
                                                @elseif($loan->status == 'returned')
                                                    <span class="badge badge-success">Dikembalikan</span>
                                                @else
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data peminjaman</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('modal')
        @include('commodities.modal.show')
        @include('commodities.modal.create')
        @include('commodities.modal.edit')
        @include('commodities.modal.import')
        @include('commodities.modal.qrcode')
        @include('commodities.modal.scanner')
        @include('commodities.modal.scanshow')
    @endpush

    @push('js')
        <script>
            function approveLoan(id) {
                Swal.fire({
                    title: 'Konfirmasi Peminjaman',
                    text: "Apakah anda yakin ingin mengkonfirmasi peminjaman ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Konfirmasi!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/commodity-loans/${id}/approve-loan`,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Berhasil!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON?.message || 'Terjadi kesalahan',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }

            function confirmReturn(id) {
                Swal.fire({
                    title: 'Konfirmasi Pengembalian',
                    text: "Apakah anda yakin ingin mengkonfirmasi pengembalian barang ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Konfirmasi!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/commodity-loans/${id}/confirm-return`,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Berhasil!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON?.message || 'Terjadi kesalahan',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }
        </script>
        @include('commodities._script')
    @endpush
</x-layout>
