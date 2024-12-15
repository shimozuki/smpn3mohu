<script>
	$(document).ready(function () {
		$(".show-modal").click(function () {
			const id = $(this).data("id");
			let url = "{{ route('api.barang.show', ':paramID') }}".replace(
				":paramID",
				id
			);

			$.ajax({
				url: url,
				header: {
					"Content-Type": "application/json",
				},
				success: (res) => {
					$("#show_commodity #item_code").val(res.data.item_code);
					$("#show_commodity #name").val(res.data.name);
					$("#show_commodity #commodity_location_id").val(
						res.data.commodity_location.name
					);
					$("#show_commodity #material").val(res.data.material);
					$("#show_commodity #brand").val(res.data.brand);
					$("#show_commodity #year_of_purchase").val(res.data.year_of_purchase);
					$("#show_commodity #condition").val(res.data.condition_name);
					$("#show_commodity #school_operational_assistance_id").val(
						res.data.school_operational_assistance.name
					);
					$("#show_commodity #note").val(res.data.note);
					$("#show_commodity #quantity").val(res.data.quantity);
					$("#show_commodity #price").val(res.data.price_formatted);
					$("#show_commodity #price_per_item").val(res.data.price_per_item_formatted);
				},
				error: (err) => {
					alert("error occured, check console");
					console.log(err);
				},
			});
		});

		$(".edit-modal").on("click", function () {
			const id = $(this).data("id");
			let url = "{{ route('api.barang.show', ':paramID') }}".replace(
				":paramID",
				id
			);

			let updateURL = "{{ route('barang.update', ':paramID') }}".replace(
				":paramID",
				id
			);

			$.ajax({
				url: url,
				method: "GET",
				header: {
					"Content-Type": "application/json",
				},
				success: (res) => {
					$("#edit_commodity form #item_code").val(res.data.item_code);
					$("#edit_commodity form #name").val(res.data.name);
					$("#edit_commodity form #commodity_location_id").val(
						res.data.commodity_location.id
					);
					$("#edit_commodity form #material").val(res.data.material);
					$("#edit_commodity form #brand").val(res.data.brand);
					$("#edit_commodity form #year_of_purchase").val(
						res.data.year_of_purchase
					);
					$("#edit_commodity form #condition").val(res.data.condition);
					$("#edit_commodity form #school_operational_assistance_id").val(
						res.data.school_operational_assistance.id
					);
					$("#edit_commodity form #note").val(res.data.note);
					$("#edit_commodity form #quantity").val(res.data.quantity);
					$("#edit_commodity form #price").val(res.data.price);
					$("#edit_commodity form #price_per_item").val(
						res.data.price_per_item
					);
					$("#edit_commodity form").attr("action", updateURL);
				},
				error: (err) => {
					alert("error occured, check console");
					console.log(err);
				},
			});
		});

		$('.qr-modal').click(function() {
    let id = $(this).data('id');
    console.log('QR modal clicked for ID:', id);
    
    $('#qrcode-container').html('<div class="spinner-border text-light" role="status"><span class="sr-only">Loading...</span></div>');
    
    $.ajax({
        url: `/barang/${id}/qrcode`,
        method: 'GET',
        success: function(response) {
            console.log('QR Code received:', response.qrcode);
            if (response.success && response.qrcode) {
                $('#qrcode-container').html(response.qrcode);
                $('.commodity-name').text(response.name || '');
                $('.commodity-code').text(response.code ? `Kode: ${response.code}` : '');
            } else {
                $('#qrcode-container').html('<div class="alert alert-danger">QR Code tidak tersedia</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Ajax Error:', error);
            $('#qrcode-container').html('<div class="alert alert-danger">Gagal memuat QR Code</div>');
        }
    });
});

// Download QR Code
$('#download-qr').click(function() {
    // Get the SVG element
    const svg = document.querySelector('#qrcode-container svg');
    
    // Create a canvas
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    
    // Create an image from the SVG
    const img = new Image();
    const svgData = new XMLSerializer().serializeToString(svg);
    const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
    const url = URL.createObjectURL(svgBlob);
    
    img.onload = function() {
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0);
        
        // Convert to PNG and download
        const pngUrl = canvas.toDataURL('image/png');
        const downloadLink = document.createElement('a');
        downloadLink.href = pngUrl;
        downloadLink.download = 'qrcode.png';
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
        URL.revokeObjectURL(url);
    }
    
    img.src = url;
});

// QR Code Scanner
let html5QrcodeScanner = null;

$('#scan_qr_modal').on('shown.bs.modal', function () {
    html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", 
        { 
            fps: 10,
            qrbox: {width: 250, height: 250},
            aspectRatio: 1.0
        }
    );
    
    function onScanSuccess(decodedText, decodedResult) {
        console.log(`Code scanned = ${decodedText}`, decodedResult);
        
        // Cari barang berdasarkan kode
        $.ajax({
            url: `/barang/search/${decodedText}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    // Tutup modal scanner
                    $('#scan_qr_modal').modal('hide');
                    
                    // Set data ke modal scan show
                    $('#commodity_id').val(response.commodity.id);
                    $('#scan_show_code').val(response.commodity.item_code);
                    $('#scan_show_name').val(response.commodity.name);
                    $('#scan_show_brand').val(response.commodity.brand);
                    $('#scan_show_material').val(response.commodity.material);
                    $('#scan_show_location').val(response.commodity.commodity_location_id);
                    $('#scan_show_purchase_year').val(response.commodity.year_of_purchase);
                    $('#scan_show_price').val(response.commodity.price);
                    
                    // Set kondisi barang
                    let condition = '';
                    switch(response.commodity.condition) {
                        case 1:
                            condition = 'Baik';
                            break;
                        case 2:
                            condition = 'Kurang Baik';
                            break;
                        case 3:
                            condition = 'Rusak Berat';
                            break;
                        default:
                            condition = '-';
                    }
                    $('#scan_show_condition').val(condition);
                    
                    // Tampilkan modal scan show
                    $('#scan_show_modal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message || 'Barang tidak ditemukan!'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat mencari barang!'
                });
            }
        });
    }

    function onScanFailure(error) {
        // Handle kegagalan scan
        console.warn(`Code scan error = ${error}`);
    }

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});

// Bersihkan scanner saat modal ditutup
$('#scan_qr_modal').on('hidden.bs.modal', function () {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
        html5QrcodeScanner = null;
    }
});

	});
</script>
