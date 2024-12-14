<style>
    #qrcode-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 200px;
        width: 100%;
        padding: 15px;
        background-color: white;
    }

    #qrcode-container svg {
        width: 100%;
        height: auto;
        max-width: 300px; /* Ukuran maksimal QR */
        min-width: 150px; /* Ukuran minimal QR */
        border: 1px solid #ddd;
        padding: 10px;
        background-color: white;
    }

    /* Responsive styling */
    @media (max-width: 576px) {
        .modal-dialog {
            margin: 0.5rem;
        }
        
        #qrcode-container {
            min-height: 150px;
            padding: 10px;
        }
        
        #qrcode-container svg {
            max-width: 200px;
        }
    }
</style>

<div class="modal fade" id="qr_code_modal" tabindex="-1" role="dialog" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrCodeModalLabel">QR Code Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid p-0">
                    <div id="qrcode-container">
                        <!-- QR code akan ditampilkan di sini -->
                    </div>
                    <div class="text-center mt-3">
                        <h5 class="commodity-name font-weight-bold mb-2"></h5>
                        <p class="commodity-code text-muted"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="download-qr">Download QR Code</button>
            </div>
        </div>
    </div>
</div>