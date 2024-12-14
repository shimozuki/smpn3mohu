<div class="modal fade" id="scan_qr_modal" tabindex="-1" role="dialog" aria-labelledby="scanQrModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scanQrModalLabel">Scan QR Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div id="qr-reader" style="width: 100%"></div>
                </div>
                <div class="alert alert-info">
                    Arahkan kamera ke QR Code untuk memindai
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/html5-qrcode"></script>