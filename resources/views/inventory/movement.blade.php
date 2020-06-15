@extends('layouts.app')
@section('title', 'Product Movement')
@section('title-of-page')
	<i class="ti-line-chart"></i> Product Movement
@endsection

@section('styles')
	
    {{-- PLUGINS CSS --}}
    {{-- date range picker --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugins/daterangepicker/daterangepicker.css') }}">
	{{-- chart js --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chartjs/Chart.min.css') }}">

@endsection

@section('content')

	<div class="card my-3">
		<div class="card-body row p-2">
			<div class="col-xl-4">
				<x-filter-dates></x-filter-dates>
			</div>
			<div class="col-xl-2"></div>
			<div class="col-xl-6 d-flex justify-content-end ">
				{{-- <button type="button" class="btn btn-warning waves-effect text-white  width-md waves-light mx-1">
					PRINT
				</button> --}}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xl-12">
			<div class="card-box">

				<h4 class="text-center text-primary">
					<i class="fas fa-syringe"></i> SALES PER QUANTITY
				</h4>
				<div class="p-2">
					<canvas id="movement" style="width: 100%; height: 350px"></canvas>
				</div>

            </div>
		</div>
	</div>
@endsection

@section('modals')
	
	<input type="hidden" id="page" value="product-movement">
	
	{{-- MODAL FOR FILTER --}}
	<div id="filter-modal" class="parent-modal"  style="display: none;">

		<div onclick="closeModal('filter-modal')" class="bg-for-modal"></div>

		<div class="modal-box edit-batch-modal">
			<i class="fas fa-times" onclick="closeModal('filter-modal')"></i>

			<h2><i class="ti-filter"></i> Filter Product Movement</h2>
			
			<form id="filter-form" method="GET" onsubmit="return productMovementDateRange()">
				@csrf
				<div class="tab active">
					<div class="d-flex">
						<div class="edit batch-edit">
							
							<h5 class="text-primary lighter imp my-3">Date Filter</h5>

							<div class="input-box">
								<label>
									<i class="ti-calendar text-primary"></i> Starting
								</label>
								<div class="input-field">
									<input type="date" id="start_date" name="start" class="for-filter">
								</div>
							</div>

							<div class="input-box">
								<label>
									<i class="ti-calendar text-danger"></i> Ending
								</label>
								<div class="input-field">
									<input type="date" id="end_date" name="end" class="for-filter">
								</div>
							</div>

							<div class="d-flex justify-content-center">
								<button type="submit" class="btn btn-outline-warning my-4">
									<i class="ti-filter"></i>
									&nbsp;&nbsp;FILTER MOVEMENT
								</button>
							</div>

							<hr>

						</div>
					</div>
				</div>
			</form>

		</div>
	</div>
	{{-- END - MODAL FOR FILTER --}}

@endsection

@section('scripts')

	{{-- PLUGINS --}}
	{{-- daterangepicker --}}
    <script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}"></script>
	
	<script src='{{asset('/plugins/chartjs/Chart.min.js')}}' type="text/javascript"></script>
	<script src='{{asset('js/inventory/movement.js')}}' type="text/javascript"></script>

@endsection