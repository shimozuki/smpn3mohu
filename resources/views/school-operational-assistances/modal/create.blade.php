<!-- Modal -->
<div class="modal fade" id="school_operational_assistance_create_modal" data-backdrop="static" data-keyboard="false"
	tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Tambah Data BOS</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ route('bantuan-dana-operasional.store') }}" method="POST">
					@csrf
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label for="name">Nama BOS</label>
								<input type="text" name="name" class="form-control @error('name', 'store') is-invalid @enderror"
									id="name" value="{{ old('name') }}" placeholder="Masukan nama..">
								@error('name', 'store')
								<div class="d-block invalid-feedback">
									{{ $message }}
								</div>
								@enderror
							</div>
						</div>

						<div class="col-lg-12">
							<div class="form-group">
								<label for="description">Deskripsi BOS</label>
								<textarea name="description" class="form-control @error('description', 'store') is-invalid @enderror"
									name="description" id="description" style="height: 100px;"
									placeholder="Masukan deskripsi (opsional)..">{{ old('description') }}</textarea>
								@error('description', 'store')
								<div class="d-block invalid-feedback">
									{{ $message }}
								</div>
								@enderror
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
						<button type="submit" class="btn btn-success">Tambah</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
