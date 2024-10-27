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
	});
</script>
