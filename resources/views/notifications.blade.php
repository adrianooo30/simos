@extends('layouts.app')
@section('title', 'Notifications')
@section('title-of-page')
	<i class="ti-bell"></i> Notifications
@endsection

@section('styles')
	{{-- daterange --}}
    <link href="{{ asset('/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
	
	<div class="row">
		<div class="col-xl-4 col-lg-4">
			<div class="card">
				<div class="card-body --notif-link">

					<x-filter-dates></x-filter-dates>

					<hr>
						
					<h4 class="text-primary">Sections</h4>

					<a href="#" class="alert alert-primary d-block --notif-type-btn" data-notif-type="out-of-stock">
						<strong class="text-primary">
							<i class="ti-shopping-cart"></i> Out of Stock
						</strong>
						<span class="badge badge-rounded badge-primary font-weight-bold float-right
									--out-of-stock-notif-count
									"></span>
					</a>

					<a href="#" class="alert alert-primary d-block --notif-type-btn" data-notif-type="critical-stock">
						<strong class="">
							<i class="ti-shopping-cart"></i> Critical Stock
						</strong>
						<span class="badge badge-rounded badge-primary font-weight-bold float-right
									--critical-stock-notif-count
									"></span>
					</a>

					<a href="#" class="alert alert-primary d-block --notif-type-btn" data-notif-type="expired">
						<strong class="">
							<i class="ti-shopping-cart"></i> Expired
						</strong>
						<span class="badge badge-rounded badge-primary font-weight-bold float-right
									--expired-notif-count
									"></span>
					</a>

					<a href="#" class="alert alert-primary d-block --notif-type-btn" data-notif-type="soon-expiring">
						<strong class="">
							<i class="ti-shopping-cart"></i> Soon Expiring
						</strong>
						<span class="badge badge-rounded badge-primary font-weight-bold float-right
									--soon-expiring-notif-count
									"></span>
					</a>

					<hr>

					{{-- <a href="#" class="alert alert-primary d-block --notif-type-btn" data-notif-type="new-order">
						<strong class="">
							<i class="ti-receipt"></i> New Order
						</strong>
						<span class="badge badge-rounded badge-primary font-weight-bold float-right">10</span>
					</a>

					<a href="#" class="alert alert-primary d-block --notif-type-btn" data-notif-type="updates-on-created-order">
						<strong class="">
							<i class="ti-receipt"></i> Updates on Created Order
						</strong>
						<span class="badge badge-rounded badge-primary font-weight-bold float-right">10</span>
					</a>

					<hr>

					<a href="#" class="alert alert-primary d-block --notif-type-btn" data-notif-type="paid-bills-in-collection">
						<strong class="">
							<i class="ti-receipt"></i> Paid Bills in Collection
						</strong>
						<span class="badge badge-rounded badge-primary font-weight-bold float-right">10</span>
					</a> --}}

					<hr>

				</div>
			</div>
		</div>
	
		{{-- different section --}}
		<div class="col-xl-8 col-lg-8">				
			<div class="card">
				<div class="card-body">

					<div class="row">
						<div class="col-xl-6">
							<div class="form-group my-1">
								<select name="filter_state" id="" class="form-control">
									<option value="all">All</option>
									<option value="unread">Unread</option>
									<option value="read">Read</option>
								</select>
								<sub class="text-muted font-weight-bold">Filter the notifications via its state.</sub>
							</div>
						</div>

						<div class="col-xl-6">
							{{-- <h4 class="text-primary --notif-type-title">Critical Stock(s)</h4> --}}
						</div>
					</div>
					
					<div class="row --notifications-parent">
						<div class="col-xl-12 my-3">
							{{-- loading --}}
							<h4 class="text-center text-muted font-weight-lighter --notif-loading">
								<div class="spinner-border text-custom m-2" role="status">
		                            <span class="sr-only">Loading...</span>
		                        </div>
							</h4>
							{{-- notifications here --}}
							<div class="--notif-content"></div>
						</div>
					</div>

					{{-- <div class="row my-3">
						<div class="col-xl-12 d-flex justify-content-center">
							<button class="btn btn-primary waves-effect wave-light font-weight-bold">
								<i class="ti-refresh"></i> Load More
							</button>
						</div>
					</div> --}}

				</div>
			</div>
		</div>

	</div>

@endsection

@section('scripts')

	{{-- PLUGINS --}}
	{{-- daterangepicker --}}
    <script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}"></script>
	
	<script src='{{asset('/js/notifications.js')}}' type="text/javascript"></script>
	
	{{-- vue framework --}}
	{{-- <script src='{{asset('js/vue/notifications.js')}}' type="text/javascript"></script> --}}

@endsection