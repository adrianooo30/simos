@extends('layouts.app')
@section('title')
	Employee - {{ $employee['full_name'] }}'s History
@endsection
@section('title-of-page')
	<i class="ti-user"></i> <span> Employee - {{ $employee['full_name'] }}'s History</span>
@endsection

{{-- style --}}
@section('styles')
	
	{{-- datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}"/>
	{{-- responsive datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.css') }}">

@endsection

@section('content')

<div class="row">
	<div class="col-xl-12">

		<div class="card p-2">
			<div class="row">
				<div class="col-xl-4 d-flex justify-content-center">
			        <img class="img-thumbnail image-150 mx-auto" src="{{ asset( $employee['profile_img'] ) }}">
				</div>

				<div class="col-xl-8">
					<div class="row">
						<div class="col-xl-6 text-center">

			                <h3 class="text-secondary lighter">{{ $employee['full_name'] }}</h3>
			                <sup class="text-muted">{{ $employee->getRoleNames()->first() }}</sup>

							<h3 class="text-warning lighter">{{ $employee['balance_format'] ?? '19,001.00' }}</h3>
							<sup class="text-muted">Total Bills</sup>
							
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-xl-6">
        <div class="card">
        	<div class="card-header">
        		<div class="row">
					<div class="col-xl-12">
						<x-filter-dates></x-filter-dates>
					</div>
				</div>
        	</div>
            <div class="card-body">
	            <h4 class="header-title mt-0">Sales overview for April</h4>
	            <div id="morris-bar-example" dir="ltr" style="height: 280px;" class="morris-chart"></div>
            </div>
        </div>
    </div><!-- end col -->

    <div class="col-xl-6">
	
        <div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-xl-12">
						<x-filter-dates></x-filter-dates>
					</div>
				</div>
			</div>
            <div class="card-body">
	            <h4 class="header-title mt-0">Collections overview for April</h4>
	            <div id="morris-line-example" dir="ltr" style="height: 280px;" class="morris-chart"></div>
            </div>
        </div>
    </div><!-- end col -->
</div>

<div class="row">
	<div class="col-xl-12">
        <div class="card-box">

            <h4 class="header-title text-primary mt-0 mb-3">Table of Sales</h4>

           <table id="sales-table" class="table table-striped bg-white text-center" style="width: 100%;">
				<thead class="text-primary">
					<td></td>
					<td>Account Name</td>
					<td>Order Date</td>
					<td>Total Cost</td>
					<td></td>
					<td></td>
				</thead>
			</table>
        </div>
    </div><!-- end col -->
</div>

@endsection

@section('dashboard-scripts')
	
	{{-- <script src="{{ asset('js/dashboard.js') }}" type="text/javascript"></script> --}}

    <!-- knob plugin -->
    <script src="{{ asset('/assets/libs/jquery-knob/jquery.knob.min.js') }}"></script>

    <!--Morris Chart-->
    <script src="{{ asset('/assets/libs/morris-js/morris.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/raphael/raphael.min.js') }}"></script>

    <!-- Dashboard init js-->
    <script src="{{ asset('/assets/js/pages/dashboard.init.js') }}"></script>

@endsection

@section('scripts')

	{{-- plugin datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
	
	{{-- responsive datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.js') }}"></script>

	<script src="{{asset('js/users/account-history.js')}}" type="text/javascript"></script>

@endsection