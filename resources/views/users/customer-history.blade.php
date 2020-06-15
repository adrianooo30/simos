@extends('layouts.app')
@section('title')
	{{ $account['account_name'] }}'s History
@endsection
@section('title-of-page')
	<i class="ti-user"></i> <span> {{ $account['account_name'] }}'s History</span>
@endsection

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
    {{-- chart.js --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chartjs/Chart.min.css') }}">

@endsection

@section('content')

<div class="row">
	<div class="col-xl-12">

		<div class="card card-shadow p-3">
			{{-- first row --}}
			<div class="card-body">
				<div class="row">
					<div class="col-xl-4 d-flex justify-content-center">
				        <img class="img-thumbnail image-150 mx-auto" src="{{ asset( $account['profile_img'] ) }}">
					</div>

					<div class="col-xl-8">
						<div class="row">
							<div class="col-xl-6 text-center">

				                <h3 class="text-secondary lighter">{{ $account['account_name'] }}</h3>
				                <sup class="text-muted font-weight-bolder">{{ $account['type'] }}</sup>

								<h5 class="text-primary lighter">
									{{ $account['address'] }}
								</h5>
								<sup class="text-muted font-weight-bolder">
									<i class="ti-home"></i>  Address
								</sup>
								
							</div>
							<div class="col-xl-6 text-center">

								<h3 class="text-danger lighter">{!! $account->pesoFormat( $account['total_bill'] ) !!}</h3>
								<sup class="text-muted font-weight-bolder">
									<i class="ti-receipt"></i> Total Bill
								</sup>

				                <h3 class="text-primary lighter">{!! $account->pesoFormat($account['excess_payment']) !!}</h3>
				                <sup class="text-muted font-weight-bolder">
				                	<i class="ti-receipt"></i> Excess Payment
				                </sup>
								
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="card-footer">
				{{-- second row --}}

				<div class="row">
					<div class="col-xl-12">
						<div class="float-right">
							<a href="{{ route('transactions.sales') }}?account_id={{ $account['id'] }}" class="btn btn-primary mx-1 waves-effect wave-light my-1">
								<i class="ti-receipt"></i> VIEW SALES
							</a>
							<a href="{{ route('transactions.collections') }}?account_id={{ $account['id'] }}" class="btn btn-success mx-1 waves-effect wave-light my-1">
								<i class="ti-receipt"></i> VIEW COLLECTIONS
							</a>
						</div>
					</div>
				</div>	

			</div>

		</div>

	</div>
</div>
<!-- end row -->

{{-- SALES --}}
<div class="row">
	<div class="col-xl-12">
		<div class="card card-shadow">

			<div class="card-header">
				<div class="d-flex justify-content-end">
					<div class="col-xl-5 float-right">
						<x-filter-dates></x-filter-dates>
					</div>
				</div>
			</div>

			<div class="card-body">
				<div class="row">
					<div class="col-xl-6 col-l-6">
						<h4 class="text-center text-gray font-weight-lighter">
							<i class="fas fa-syringe"></i> Sales per Product
						</h4>
						<div class="p-2">
							<canvas id="sales" style="width: 100%; height: 350px"></canvas>
						</div>
					</div>

					<div class="col-xl-6 col-l-6">
						<h4 class="text-center text-gray font-weight-lighter">
							<i class="fas fa-syringe"></i> Collections per Product
						</h4>
						<div class="p-2">
							<canvas id="collections" style="width: 100%; height: 350px"></canvas>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<div class="row">
	<div class="col-xl-12">
        <div class="card card-shadow">

            <div class="card-body">
            	{{-- title --}}
	            <h3 class="header-title text-primary mt-0">List of Bills</h3>
	            <h6 class="text-muted">Here are the list of bills for <span class="text-primary">({{ $account['account_name'] }})</span>, some of this might be paid using the excess payment of the account.</h6>

	            <br>

	            {{-- sections content --}}
	            <table id="bills-can-paid-using-excess-table" class="table table-striped bg-white text-center my-3" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td>Delivery Date</td>
						<td>Total Bill</td>
						<td>Status</td>
						<td></td>
					</thead>
				</table>
            </div>

        </div>
    </div><!-- end col -->

</div>

{{-- <div class="row">
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
</div> --}}

@endsection

{{-- @section('dashboard-scripts') --}}
	
	{{-- <script src="{{ asset('js/dashboard.js') }}" type="text/javascript"></script> --}}

    <!-- knob plugin -->
    {{-- <script src="{{ asset('/assets/libs/jquery-knob/jquery.knob.min.js') }}"></script> --}}

    <!--Morris Chart-->
    {{-- <script src="{{ asset('/assets/libs/morris-js/morris.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('/assets/libs/raphael/raphael.min.js') }}"></script> --}}

    <!-- Dashboard init js-->
    {{-- <script src="{{ asset('/assets/js/pages/dashboard.init.js') }}"></script> --}}

{{-- @endsection --}}

@section('scripts')

	{{-- plugin datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
	
	{{-- responsive datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.js') }}"></script>

	{{-- daterangepicker --}}
    <script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}"></script>
    {{-- chart.js --}}
	<script src='{{asset('/plugins/chartjs/Chart.min.js')}}' type="text/javascript"></script>
	
	<script type="text/javascript">
		var accountId = {{ $account['id'] }};
	</script>
	<script src="{{asset('js/users/customer-history.js')}}" type="text/javascript"></script>

@endsection