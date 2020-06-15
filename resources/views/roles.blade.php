@extends('layouts.app')
@section('title', 'Roles Management')
@section('title-of-page')
	<i class="ti-user"></i> Roles Management
@endsection

@section('roles-tab', 'active')

@section('styles')

	{{-- <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet"> --}}

	<!-- Plugins css -->

@endsection

@section('content')
	<div class="row">
		<div class="col-xl-4">
			<div class="card">
				<div class="card-body">
					
					<h4 class="text-primary">Position/Roles</h4>
					<hr>

					@forelse($roles ?? '' as $role)
						<a href="javascript: void(0);" class="alert alert-primary d-block --position-link" data-position="{{ $role['id'] }}">
							<strong class="{{ $loop->first ? 'text-primary' : '' }}">
								<i class="ti-user"></i>
								<span>{{ $role['name'] }}</span>
							</strong>
						</a>
						@empty
						<a href="#" class="alert alert-primary d-block">
							<strong><i class="ti-user"></i> No Positions</strong>
						</a>
					@endforelse

				</div>
			</div>
		</div>
	
		{{-- different section --}}
		<div class="col-xl-8">
			<div class="card">
				<div class="card-body">
					{{-- @permissionsList --}}
					<h4 class="text-center text-muted font-weight-lighter" id="permissions-loading">
						<div class="spinner-border text-custom m-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
					</h4>
					<div id="permissions-list">
						{{--  --}}
					</div>
				</div>
			</div>
		</div>

	</div>
@endsection

@section('scripts')

	<!-- Init js-->
	{{-- <script src="assets/js/pages/form-advanced.init.js"></script> --}}
	
	<script src='{{asset('js/roles.js')}}' type="text/javascript"></script>

@endsection