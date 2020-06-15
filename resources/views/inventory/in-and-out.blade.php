@extends('layouts.app')
@section('title', 'In and Out Products')
@section('title-of-page')
	<i class="fas fa-syringe"></i> <span>In and Out Products</span>
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
		</div>
	</div>

	<div class="row">
    	<div class="col-xl-12">
            <div class="card-box">

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">Products In</h4>

                {{-- sections content --}}
				<table id="product-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td></td>
						<td>Product Name</td>
						<td>Date Added</td>
						<td>Batch No</td>
					</thead>
				</table>

            </div>
        </div><!-- end col -->
    </div>

    <hr>

    {{-- out products --}}

    <div class="card my-3">
		<div class="card-body row p-2">
			<div class="col-xl-4">
				<x-filter-dates></x-filter-dates>
			</div>
		</div>
	</div>

	<div class="row">
    	<div class="col-xl-12">
            <div class="card-box">

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">Products Out</h4>

                {{-- sections content --}}
				<table id="product-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td></td>
						<td>Product Name</td>
						<td>Date Added</td>
						<td>Batch No</td>
					</thead>
				</table>

            </div>
        </div><!-- end col -->
    </div>

@endsection

@section('scripts')

	{{-- plugin datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
	
	{{-- responsive datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.js') }}"></script>

	{{-- PLUGINS --}}
	{{-- daterangepicker --}}
    <script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}"></script>

	{{-- in and out scripts --}}
	<script src="{{ asset('/js/datatables/in-and-out.js') }}" type="text/javascript"></script>

@endsection