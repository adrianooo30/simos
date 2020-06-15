@extends('layouts.app')
@section('title', 'Soon Expiring Products')
@section('title-of-page')
	<i class="ti-calendar fa-lg"></i> <span>Soon Expiring Products</span>
@endsection

{{-- style --}}
@section('styles')
	
	{{-- datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}"/>
	{{-- responsive datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.css') }}">
	{{-- dropify --}}
    <link href="{{ asset('/assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    {{-- daterange --}}
    <link href="{{ asset('/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
	
@endsection

@section('content')
	
	{{-- <div class="card my-3">
		<div class="card-body row  p-2">
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
	</div> --}}

	<div class="row">
    	<div class="col-xl-12">
            <div class="card-box">

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">Soon Expiring Table</h4>

                {{-- sections content --}}
				<table id="soon-expiring-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td>Product Name</td>
						<td>Batch Number</td>
						<td>Expiry Date</td>
						<td>Day(s) Before Expiration</td>
					</thead>
				</table>

            </div>
        </div><!-- end col -->
    </div>
@endsection

@section('modals')
	
	{{--  --}}

@endsection

@section('scripts')

	{{-- plugin datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
	
	{{-- responsive datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.js') }}"></script>


	{{-- daterangepicker --}}
    <script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}"></script>
	
	{{-- <script src="{{asset('js/manipulate/inventory/soon-expiring.js')}}" type="text/javascript"></script> --}}
	<script src="{{ asset('/js/datatables/soon-expiring.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/js/inventory/soon-expiring.js') }}" type="text/javascript"></script>

@endsection