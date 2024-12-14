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
	});
</script>
