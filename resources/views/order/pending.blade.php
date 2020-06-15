@extends('layouts.app')
@section('title', 'Pending Orders')
@section('title-of-page')
	<i class="ti-shopping-cart"></i> Pending Orders</span>
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

	<div class="card my-3">
		<div class="card-body row p-2">
			<div class="col-xl-4">
				<x-filter-dates></x-filter-dates>
			</div>
			<div class="col-xl-2"></div>
			<div class="col-xl-6 d-flex justify-content-end ">
				{{-- <button type="button" class="btn btn-warning waves-effect text-white  width-md waves-light mx-1" data-toggle="modal" data-target="#pending-modal">
					PRINT
				</button> --}}
			</div>
		</div>
	</div>

	<div class="row">
    	<div class="col-xl-12">
            <div class="card-box">

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">Table of Pending Orders</h4>

                {{-- sections content --}}
               <table id="pending-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td></td>
						<td class="font-14">Account Name</td>
						<td class="font-14">Order Date</td>
						<td class="font-14">Total Cost</td>
						<td></td>
					</thead>
				</table>

            </div>
        </div><!-- end col -->

    </div>
@endsection

@section('modals')
	
	<!--  Modal content for the above example -->
	<div id="pending-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h4 class="modal-title">Pending Order Details</h4>
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
                    </ul>

                    {{-- TAB CONTENTS --}}
					<div class="tab-content">
						{{-- TAB ONE --}}
                        <div role="tabpanel" class="tab-pane fade show active" id="tab-1">
                        	 {{--  --}}
                        </div>
						
						{{-- TAB TWO --}}
                        <div role="tabpanel" class="tab-pane fade show" id="tab-2">
                        	<table id="order-medicine-table" class="table table-striped bg-white text-center" style="width: 100%;">
								<thead class="text-primary">
									<td></td>
									<td></td>
									<td>Product Name</td>
									<td>Batch No</td>
									<td>Quantity</td>
									<td>Total Cost</td>
								</thead>
							</table>
                        </div>

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

	{{-- filtering of dates --}}
	<script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}"></script>
	
	{{-- datatables --}}
	<script type="text/javascript" src="{{ asset('js/datatables/pending.js') }}"></script>
	{{-- script --}}
	<script src="{{asset('js/order/pending.js')}}" type="text/javascript"></script>

@endsection