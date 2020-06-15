@extends('layouts.app')
@section('title', 'Returned Products')
@section('title-of-page')
	<i class="ti-import fa-lg"></i> <span>Returned Products</span>
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
		<div class="card-body row  p-2">
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

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">Table of Returned Products</h4>

				<table id="returns-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td>Product Name</td>
						<td>Batch Number</td>
						<td>Return Date</td>
						<td>Reason</td>
					</thead>
				</table>

            </div>
        </div><!-- end col -->
    </div>
@endsection

@section('modals')
	
	{{-- SECTION FOR MODALS --}}

@endsection

@section('scripts')

	{{-- <script src="{{asset('js/manipulate/inventory/returnee.js')}}" type="text/javascript"></script> --}}

	{{-- plugin datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
	
	{{-- responsive datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.js') }}"></script>

	{{-- PLUGINS --}}
	{{-- daterangepicker --}}
    <script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}"></script>

	<script src="{{asset('js/datatables/returns.js')}}" type="text/javascript"></script>
	<script src="{{asset('js/inventory/returns.js')}}" type="text/javascript"></script>

@endsection