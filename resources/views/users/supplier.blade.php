@extends('layouts.app')
@section('title', 'Supplier')
@section('title-of-page')
	<i class="ti-user"></i> Supplier
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
				@can('add_supplier')
					<button type="button" class="btn btn-primary waves-effect  width-md waves-light mx-1" data-toggle="modal" data-target="#add-supplier-modal">
						ADD SUPPLIER
					</button>
				@endcan
			</div>
		</div>
	</div>

	<div class="row">
    	<div class="col-xl-12">
            <div class="card-box">

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">Supplier Table</h4>

                {{-- sections content --}}
				<table id="supplier-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td>Supplier Name</td>
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
	
	@can('add_supplier')
		<!--  Modal content for the above example -->
	    <div id="add-supplier-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h4 class="modal-title">Add Supplier</h4>
	                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                </div>
	                <div class="modal-body">
	                   	
	                    <form id="supplier-add-form" method="post">
	                    	@supplierForm([
		                    	'inputClassName' => 'for-add-supplier',
		                    	'saveBtnName' => 'SAVE ACCOUNT',
		                    ])
	                    </form>

	                </div>
	            </div><!-- /.modal-content -->
	        </div><!-- /.modal-dialog -->
	    </div><!-- /.modal -->
	@endcan
	
	@can('edit_supplier')
		<!--  Modal content for the above example -->
	    <div id="edit-supplier-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h4 class="modal-title">Edit Supplier</h4>
	                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                </div>
	                <div class="modal-body">
	                   	
	                    <form id="supplier-update-form">
	                    	@supplierForm([
		                    	'inputClassName' => 'for-update-supplier',
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
	<script type="text/javascript" src="{{ asset('js/datatables/supplier.js') }}"></script>

	<script src="{{asset('js/users/supplier.js')}}" type="text/javascript"></script>

@endsection