@extends('layouts.app')
@section('title', 'Product Management')
@section('title-of-page')
	<i class="fas fa-syringe"></i> <span>Product Management</span>
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

<div class="row">
    	<div class="col-xl-12">
            <div class="card-box">

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">Product Table</h4>

				{!! $dataTable->table([], true) !!}

            </div>
        </div><!-- end col -->
    </div>
@endsection

@section('scripts')
	{{-- plugin datatables --}}
    <script src="{{ asset('/assets/jquery/dist/jquery.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>

	{!! $dataTable->scripts() !!}
@endsection