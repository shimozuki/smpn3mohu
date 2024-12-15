<head>
    <!-- ... -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- ... -->
</head>
<div class="modal fade" id="scan_show_modal" tabindex="-1" role="dialog" aria-labelledby="scanShowModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scanShowModalLabel">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="loanForm">
                    @csrf
                    <input type="hidden" id="commodity_id" name="commodity_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Barang</label>
                                <input type="text" class="form-control" id="scan_show_code" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" class="form-control" id="scan_show_name" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Merk</label>
                                <input type="text" class="form-control" id="scan_show_brand" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bahan</label>
                                <input type="text" class="form-control" id="scan_show_material" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kondisi</label>
                                <input type="text" class="form-control" id="scan_show_condition" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lokasi</label>
                                <input type="text" class="form-control" id="scan_show_location" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tahun Pembelian</label>
                                <input type="text" class="form-control" id="scan_show_purchase_year" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga</label>
                                <input type="text" class="form-control" id="scan_show_price" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="due_date">Tanggal Pengembalian <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="due_date" name="due_date" required>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="purpose" name="purpose" value="Peminjaman Barang">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="submitLoan()">Pinjam</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script>
    function submitLoan() {
        let formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            commodity_id: $('#commodity_id').val(),
            quantity: $('#quantity').val(),
            due_date: $('#due_date').val(),
            purpose: $('#purpose').val(),
        };
        console.log('cekdata :',formData);

        $.ajax({
            url: '/commodity-loans',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#scan_show_modal').modal('hide');
                            $('#loanForm')[0].reset();
                        }
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan pada server',
                    showConfirmButton: true
                });
            }
        });
    }
</script>
