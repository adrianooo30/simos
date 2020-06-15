@extends('layouts.app')
@section('title', 'Re DR\'s')
@section('title-of-page')
	<i class="ti-credit-card"></i> Re DR's</span>
@endsection

{{-- style --}}
@section('styles')
	
	{{-- datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}"/>
	{{-- responsive datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.css') }}">
	{{-- daterange --}}
    <link href="{{ asset('/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
<!-- main working station -->
<div id="main-card">

	<div class="card my-3">
		<div class="card-body row p-2">
			<div class="col-xl-4">
				{{-- <x-filter-dates></x-filter-dates> --}}
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

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">Table of Re DR's</h4>

                {{-- sections content --}}
               <table id="re-dr-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td></td>
						<td>Account Name</td>
						<td>Total Cost</td>
						<td></td>
					</thead>
				</table>

            </div>
        </div><!-- end col -->

    </div>


</div>
	<!-- end of main working station -->

@endsection

@section('modals')

	{{-- @can('re_deliver_order') --}}
		<!--  Modal content for the above example -->
		<div id="re-deliver-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
		    <div class="modal-dialog modal-lg">
		        <div class="modal-content">
		            <div class="modal-header">
		                <h4 class="modal-title">Record Delivery Details</h4>
		                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		            </div>
		            <div class="modal-body p-4">
		             
			            <form id="re-delivery-form" method="post">
			            	@csrf
							<div class="row">
								<div class="col-xl-6">
					
								    <input type="hidden" name="account_id" value="" class="for-re-delivery">

									<div class="form-group">
									    <label>
									    	<i class="ti-receipt text-primary"></i> Receipt Number
									    </label>

										<div class="input-group">
											<div class="input-group-append">
										        <span class="input-group-addon">
										        	<span class="input-group">
										        		<select name="receipt_type" class="form-control for-re-delivery">
										        			<option>DR</option>
										        			<option>SI</option>
										        		</select>
										        	</span>
										        </span>
										    </div>
										    <input type="number" name="receipt_no" parsley-trigger="change" required
									           placeholder="--receipt no--" class="form-control for-re-delivery" min="0">
										</div><!-- input-group -->
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
									    <label>
									    	<i class="ti-calendar text-primary"></i> Delivery Date
									    </label>
									    
										<div class="input-group">
										    <input type="date" name="delivery_date" parsley-trigger="change" required
									           placeholder="--re-delivery date--" class="form-control --date-today for-re-delivery">
										</div><!-- input-group -->
									</div>

									<div class="form-group">
										<label for="">
											<i class="ti-user text-primary"></i> Delivered By
										</label>
										<div class="input-group">
											<input type="hidden" name="employee_id" class="for-re-delivery" value="{{ Auth::id() }}">
											<input type="text" name="delivered_by" parsley-trigger="change" required readonly 
									           placeholder="--delivered by--" class="form-control for-re-delivery" value="{{ Auth::user()['full_name'] }}">
										</div>
									</div>
								</div>
							</div>
							{{-- end of row --}}

							<hr class="border-top">

							<h4 class="text-primary"><!-- Suggested  -->Product's Batch Numbers</h4>

						{{-- table of products for replacing --}}
							<table id="replaced-product-table" class="table table-striped bg-white text-center my-3" style="width: 100%;">
								<thead class="text-primary">
									<td></td>
									<td>Product Name</td>
									<td>Batch No</td>
									<td>Quantity</td>
									{{-- <td></td> --}}
								</thead>
							</table>

							<hr>

							<div class="form-group d-flex justify-content-center">
								<button class="btn btn-primary waves-effect waves-light" type="submit">
							        SAVE DELIVERY
							    </button>
							</div>

						</form>

		            </div>
		        </div><!-- /.modal-content -->
		    </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	{{-- @endcan --}}

@endsection

@section('scripts')
	
	{{-- <script src='{{asset('js/manipulate/order/track.js')}}' type="text/javascript"></script> --}}
	
	{{-- plugin datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
	
	{{-- responsive datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.js') }}"></script>

	{{-- daterangepicker --}}
    <script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}"></script>

	{{-- datatables --}}
	<script type="text/javascript" src="{{ asset('js/datatables/re-dr.js') }}"></script>
	
	{{-- all most all of forms and in modal viewing datas --}}
	<script type="text/javascript" src='{{asset('js/order/re-dr.js')}}'></script>

@endsection