@extends('layouts.app')
@section('title', 'Sales Management')
@section('title-of-page')
	<i class="ti-shopping-cart"></i> <span>Sales Management</span>
@endsection

@section('sales-tab', 'active')

{{-- style --}}
@section('styles')
	
	{{-- datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}"/>
	{{-- responsive datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.css') }}">
	{{-- datatables button --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.css') }}">
	{{-- daterange --}}
    <link href="{{ asset('/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />

	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chartjs/Chart.min.css') }}">

@endsection

@section('content')
	<!-- main working station -->

	<div class="card my-3">
		<div class="card-body row p-2">
			<div class="col-xl-4">
				<x-filter-dates></x-filter-dates>
			</div>
			<div class="col-xl-2"></div>
			<div class="col-xl-6 d-flex justify-content-end ">
				<button type="button" class="btn btn-warning waves-effect text-white  width-md waves-light mx-1">
					PRINT
				</button>
			</div>
		</div>
	</div>

	<div class="row">
    	<div class="col-xl-12">
            <div class="card-box">

            	{{-- <div id="alert-sections">
            		<div class="alert alert-info --alert-box">
	       				<div class="row">
	       					<div class="col-xl-6">
			       				<strong>There are (4) new sales.</strong> <br>
			            		<sub class="text-muted font-weight-bolder">Want to reload the table?</sub>
		       				</div>
		       				<div class="col-xl-6">
		       					<button class="btn btn-primary waves-effect wave-light float-right --reload-table-btn">
		       						<i class="ti-reload"></i> RELOAD TABLE
		       					</button>
		       				</div>
	       				</div>
	            	</div>
            	</div> --}}

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">Table of Sales</h4>

                {{-- sections content --}}
                <table id="sales-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td></td>
						<td>Account Name</td>
						<td>Delivery Date</td>
						<td>Total Cost</td>
						<td>Status</td>
						<td></td>
					</thead>
				</table>

            </div>
        </div><!-- end col -->

    </div>

	<div class="row">
		<div class="col-xl-12">
			<div class="card-box">
				<div class="row">
					<div class="col-xl-6 col-l-6">
						<h4 class="text-center text-primary">
							<i class="fas fa-syringe"></i> Sales Per Product
						</h4>
						<div class="p-2">
							<canvas id="sales-per-product" style="width: 100%; height: 350px"></canvas>
						</div>
					</div>
					<div class="col-xl-6 col-l-6">
						<h4 class="text-center text-primary">
							<i class="ti-user"></i> Sales Per Account
						</h4>
						<div class="p-2">
							<canvas id="sales-per-account" style="width: 100%; height: 350px"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

{{-- 	<div class="row">
		@can('view_employee_qoutas')
			<div class="col-xl-12" id="psr-qoutas">
		    	@qoutasDisplay
		    </div>
		@endcan
	</div> --}}

	<div class="row">
    	<div class="col-xl-12">
    		<div class="card-box">
    				
				<div class="col-8 mx-auto">
					<h4 class="header-title text-primary mt-0 mb-3">
						<i class="ti-user"></i> PSR Sales
					</h4>

				    {{-- sections content --}}
				   <table id="qoutas-table" class="table table-striped bg-white text-center" style="width: 100%;">
						<thead class="text-primary">
							<td></td>
							<td class="font-14">Employee Name</td>
							<td class="font-14">Peso Sales</td>
						</thead>
					</table>
				</div>

    		</div>
    	</div>
    </div>

@endsection

@section('modals')
	
	<!--  Modal content for the above example -->
    <div id="sales-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Sales Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                   
                   <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="#tab-1" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">Details</span>            
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tab-2" data-toggle="tab" aria-expanded="false" class="nav-link">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">Ordered Product</span>            
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="#tab-3" data-toggle="tab" aria-expanded="false" class="nav-link">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">Collections</span>            
                            </a>
                        </li> --}}
                        @can('record_returns')
							<li class="nav-item">
	                            <a href="#tab-4" data-toggle="tab" aria-expanded="false" class="nav-link">
	                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
	                                <span class="d-none d-sm-block">Record Returns</span>            
	                            </a>
	                        </li>
                        @endcan
                        {{-- <li class="nav-item">
                            <a href="#tab-5" data-toggle="tab" aria-expanded="false" class="nav-link">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">Re DR's</span>            
                            </a>
                        </li> --}}
                    </ul>

                    {{-- TAB CONTENTS --}}
					<div class="tab-content">
						{{-- TAB ONE --}}
                        <div role="tabpanel" class="tab-pane fade show active" id="tab-1">
                        	 {{--  --}}
                        </div>
	
						<div role="tabpanel" class="tab-pane fade show" id="tab-2">
							{{-- <div class="container my-4"> --}}
								<table id="order-medicine-table" class="table table-striped bg-white text-center" style="width: 100%;">
									<thead class="text-primary">
										<td></td>
										<td>Product Name</td>
										<td>Batch No</td>
										<td>Quantity</td>
										<td>Total Cost</td>
									</thead>
								</table>
							{{-- </div> --}}
						</div>

						{{-- <div role="tabpanel" class="tab-pane fade show" id="tab-3"> --}}
							{{-- <div class="container my-4"> --}}
{{-- 								<table id="collections-table" class="table table-striped bg-white text-center" style="width: 100%;">
									<thead class="text-primary">
										<td>Receipt No</td>
										<td>Collection Date</td>
										<td>Collected Amount</td>
										<td></td>
									</thead>
								</table> --}}
							{{-- </div> --}}
						{{-- </div> --}}

						@can('record_returns')
							<div role="tabpanel" class="tab-pane fade show" id="tab-4">
								<form action="{{ route('returns.store') }}" id="return-product-form">
								{{-- @returnProduct --}}
								</form>
							</div>
						@endcan

                    </div>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('scripts')
	
	{{-- plugin datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
	
	{{-- responsive datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.js') }}"></script>

	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-buttons-bs4/js/buttons.bootstrap4.js') }}"></script>

	{{-- PLUGINS --}}
	{{-- daterangepicker --}}
    <script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}"></script>
    {{-- chart.js --}}
	<script src='{{asset('/plugins/chartjs/Chart.min.js')}}' type="text/javascript"></script>

	{{-- datatables --}}
	<script type="text/javascript" src="{{ asset('/js/datatables/sales.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/transactions/sales-chart.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/datatables/qouta.js') }}"></script>

	{{-- customs --}}
	<script src="{{asset('/js/transactions/sales.js')}}" type="text/javascript"></script>
	<script src="{{asset('/js/transactions/return.js')}}" type="text/javascript"></script>

@endsection