@extends('layouts.app')
@section('title', 'Collections Management')
@section('title-of-page')
	<i class="ti-receipt"></i> <span>Collections Management</span>
@endsection

{{-- style --}}
@section('styles')
	
	{{-- datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}"/>
	{{-- responsive datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.css') }}">
	{{-- daterange --}}
    <link href="{{ asset('/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />

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
			       				<strong>There are (5) new collections.</strong> <br>
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
                <h4 class="header-title text-primary mt-0 mb-3">Table of Collections</h4>

                {{-- sections content --}}
               <table id="collection-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td></td>
						<td class="font-14">Account Name</td>
						<td class="font-14">Collection Date</td>
						<td class="font-14">Collected Amount</td>
						<td></td>
					</thead>
				</table>

            </div>
        </div><!-- end col -->

    </div>
	
    <div class="row">
		<div class="col-xl-12">
			<div class="card-box">
				<div class="row py-3">
					<div class="col-xl-6 col-l-6">
						<h4 class="text-center text-primary">
							<i class="fas fa-syringe"></i> Collections Per Product
						</h4>
						<div class="p-2">
							<canvas id="collections-per-product" style="width: 100%; height: 350px"></canvas>
						</div>
					</div>
					<div class="col-xl-6 col-l-6">
						<h4 class="text-center text-primary">
							<i class="ti-user"></i> Collections Per Account
						</h4>
						<div class="p-2">
							<canvas id="collections-per-account" style="width: 100%; height: 350px"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>

{{--     <div class="row">
        @can('view_employee_qoutas')
			<div class="col-xl-12">
	        	@qoutasDisplay
	        </div>
        @endcan
    </div> --}}


@endsection

@section('modals')
	
	<!--  Modal content for the above example -->
	<div id="collections-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h4 class="modal-title">Collection Details</h4>
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
                                <span class="d-none d-sm-block">Paid Bills</span>            
                            </a>
                        </li>
                        @can('record_deposit')
	                        <li class="nav-item">
	                            <a href="#tab-3" data-toggle="tab" aria-expanded="false" class="nav-link">
	                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
	                                <span class="d-none d-sm-block">Deposit</span>            
	                            </a>
	                        </li>
                        @endcan
                    </ul>

                    {{-- TAB CONTENTS --}}
					<div class="tab-content">
						{{-- TAB ONE --}}
                        <div role="tabpanel" class="tab-pane fade show active" id="tab-1">
							{{-- collection modal details --}}
                        </div>
						
						{{-- tab 2 --}}
                        <div role="tabpanel" class="tab-pane fade show" id="tab-2">
							{{-- <div class="container my-4"> --}}
								<table id="paid-bills-table" class="table table-striped bg-white text-center" style="width: 100%;">
									<thead class="text-primary">
										<td>Receipt No</td>
										<td>Total Cost</td>
										<td>Paid Amount</td>
										<td></td>
									</thead>
								</table>
							{{-- </div> --}}
                        </div>

                        @can('record_deposit')
							<div role="tabpanel" class="tab-pane fade show" id="tab-3">

	                        	<div id="--title-deposit"></div>
							
							<form id="deposit-form" method="POST">
								<div class="row">
		
									<input type="hidden" name="collection_transaction_id" class="for-deposit">

									<div class="col-xl-6">
										<div class="form-group">
										    <label>Bank</label>
										    <input type="text" name="bank" parsley-trigger="change" required
										           placeholder="--bank--" class="form-control for-deposit">
										</div>

										<div class="form-group">
										    <label>Deposit Date</label>
										    <input type="date" name="date_of_deposit" parsley-trigger="change" required
										           placeholder="--deposit date--" class="form-control for-deposit --date-today">
										</div>
									</div>

									<div class="col-xl-6">
										
										<input type="hidden" name="employee_id" class="for-deposit" value="{{ Auth::id() }}">

										<div class="form-group">
										    <label>Deposit By</label>
										    <input type="text" name="deposit_by" parsley-trigger="change" required
										           placeholder="--deposit by--" class="form-control for-deposit" readonly disabled value="{{ Auth::user()['full_name'] }}">
										</div>
									</div>
								</div>

								<div class="form-group">
									<button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
								        SAVE DEPOSIT
								    </button>
								</div>
							</form>

	                        </div>
                        @endcan

					</div>

	            </div>
	        </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<!--  Modal content for the above example -->
	<div class="modal fade" id="paid-order-medicines-modal" tabindex="-1" role="dialog" aria-labelledby="paid-order-medicines" aria-hidden="true" style="display: none;">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h4 class="modal-title">
                		<span class="text-primary">
                			<i class="ti-receipt"></i> DR1023
                		</span><br>
                		<sup class="font-12 text-muted font-weight-bolder">Paid Order Medicines</sup>
	                </h4>
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	            </div>
	            <div class="modal-body">
	                <table id="paid-order-medicines-table" class="table table-striped bg-white text-center" style="width: 100%;">
						<thead class="text-primary">
							<td></td>
							<td class="font-14">Product Name</td>
							<td class="font-14">Paid Quantity</td>
							<td class="font-14">Paid Amount</td>
						</thead>
					</table>
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

	{{-- filtering of dates --}}
	<script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}"></script>
	<script src='{{asset('/plugins/chartjs/Chart.min.js')}}' type="text/javascript"></script>

	{{-- datatables --}}
	<script type="text/javascript" src="{{ asset('/js/datatables/collection.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/transactions/collections-chart.js') }}"></script>
	{{-- <script type="text/javascript" src="{{ asset('/js/datatables/qouta.js') }}"></script> --}}

	<script src="{{ asset('/js/transactions/collection.js') }}" type="text/javascript"></script>

	<script type="text/javascript">
		// PAID ORDER MEDICINE
		$('#paid-order-medicines-modal').on('show.bs.modal', function(e){
			$('#collections-modal').modal('hide');
		});
		$('#paid-order-medicines-modal').on('hidden.bs.modal', function(e){
			$('#collections-modal').modal('show');
		});

		$('#collections-modal').on('hide.bs.modal', function(e){
			$('.modal-backdrop.fade.show').remove();
		});
	</script>

@endsection