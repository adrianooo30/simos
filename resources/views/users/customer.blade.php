@extends('layouts.app')
@section('title', 'Customer\'s Account')
@section('title-of-page')
	<i class="ti-user"></i> <span> Customer's Account</span>
@endsection

{{-- style --}}
@section('styles')
	
	{{-- datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}"/>
	{{-- responsive datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.css') }}">
	{{-- dropify --}}
    <link href="{{ asset('/assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

	<div class="card my-3">
		<div class="card-body row p-2">
			<div class="col-xl-4">
				{{--  --}}
			</div>
			<div class="col-xl-2"></div>
			<div class="col-xl-6 d-flex justify-content-end ">
				@can('add_account')
					<button type="button" class="btn btn-primary waves-effect  width-md waves-light mx-1" data-toggle="modal" data-target="#add-customer-account-modal">
						ADD ACCOUNT
					</button>
				@endcan
			</div>
		</div>
	</div>

	<div class="row">
    	<div class="col-xl-12">
            <div class="card-box">

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">Accounts Table</h4>

                {{-- sections content --}}
				<table id="account-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td>Account Name</td>
						<td>Address</td>
						<td>Contact</td>
						<td></td>
					</thead>
				</table>

            </div>
        </div><!-- end col -->
    </div>

@endsection

@section('modals')

	@can('add_account')
		<!--  Modal content for the above example -->
	    <div id="add-customer-account-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h4 class="modal-title">Add New Customer Account</h4>
	                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                </div>
	                <div class="modal-body">
	                    
	                    <form id="form-add-account" enctype="multipart/form-data" method="POST">
	                    	@customerForm([
		                    	'inputClassName' => 'for-add-account',
		                    	'saveBtnName' => 'SAVE ACCOUNT',
		                    ])
	                    </form>

	                </div>
	            </div><!-- /.modal-content -->
	        </div><!-- /.modal-dialog -->
	    </div><!-- /.modal -->
	@endcan
	
	@can('edit_account')
	    <!--  Modal content for the above example -->
	    <div id="edit-customer-account-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h4 class="modal-title">Edit Customer Account</h4>
	                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                </div>
	                <div class="modal-body">

	                	<form id="form-update-account" enctype="multipart/form-data" method="POST">
	                    	@customerForm([
		                    	'inputClassName' => 'for-update-account',
		                    	'saveBtnName' => 'SAVE CHANGES',
		                    ])
	                    </form>
	                    
	                </div>
	            </div><!-- /.modal-content -->
	        </div><!-- /.modal-dialog -->
	    </div><!-- /.modal -->
    @endcan

@endsection

@section('scripts')

	{{-- plugin datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
	
	{{-- responsive datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.js') }}"></script>

	{{-- PLUGINS --}}
	<!-- dropify js -->
	<script src="{{ asset('/assets/libs/dropify/dropify.min.js') }}"></script>
	<!-- form-upload init -->
	<script src="{{ asset('/assets/js/pages/form-fileupload.init.js') }}"></script>

	{{-- datatables --}}
	<script type="text/javascript" src="{{ asset('js/datatables/customer.js') }}"></script>

	<script src="{{asset('js/users/customer.js')}}" type="text/javascript"></script>

@endsection