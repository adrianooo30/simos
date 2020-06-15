@extends('layouts.app')
@section('title', 'Backup / Restore')
@section('title-of-page')
	<i class="ti-dashboard"></i> Backup / Restore
@endsection

@section('styles')
	{{-- dropify --}}
    <link href="{{ asset('/assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

	<div class="d-flex justify-content-center my-3">
		<div class="card card-shadow col-xl-8 px-0">
			<div class="card-header bg-white py-2">
				<h4 class="text-primary">Backup / Restore</h4>
			</div>
			<div class="card-body">
				<div>
					<button type="button" class="btn btn-primary waves-effect wave-light d-block">
						<i class="fas fa-file-download"></i>
						<strong>Download Database / File</strong>
					</button>
					<br>
					<sub class="text-muted font-10 py-2">Download the database and its generated images.</sub>
					<sub class="text-muted font-10 py-1">The backup of datatabase and its corresponding files are automatic. Every working days at 5pm.</sub>
				</div>

				<hr class="border-top my-3">
				
				<div>
					{{-- <div class="form-group py-2">
						<input type="file" class="form-control cursor-pointer">
					</div> --}}
	
					<div class="row">
						<div class="col-xl-12">
            				<input type="file" name="database" class="dropify" />
						</div>
						{{-- <div class="col-xl-6">
            				<input type="file" name="file" class="dropify" />
						</div> --}}
					</div>

					<div class="my-3">
						<button type="button" class="btn btn-warning waves-effect wave-light d-block">
						<i class="fas fa-file-upload"></i>
							<strong>Restore Database / File</strong>
						</button>
						<br>
						<sub class="text-muted font-10 py-1">The file must be a type of (sql) and in the second must be a type of (zip).</sub>
						<sub class="text-muted font-10 py-1">Restoring any records must backup first the latest data, before restoring any previous files.</sub>
					</div>
				</div>

			</div>
		</div>
	</div>
	
@endsection

@section('modals')
	

@endsection

@section('scripts')
	
	<!-- dropify js -->
	<script src="{{ asset('/assets/libs/dropify/dropify.min.js') }}"></script>
	<!-- form-upload init -->
	<script src="{{ asset('/assets/js/pages/form-fileupload.init.js') }}"></script>

@endsection