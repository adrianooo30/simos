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

	<div class="card my-3">
		<div class="card-body row p-2">
			<div class="col-xl-4">
				<x-filter-dates></x-filter-dates>
			</div>
			<div class="col-xl-2"></div>
			<div class="col-xl-6 d-flex justify-content-end ">
				@can('add_product')
					<button type="button" class="btn btn-primary waves-effect  width-md waves-light mx-1" data-toggle="modal" data-target="#add-new-product-modal">
						ADD NEW MEDICINE
					</button>
				@endcan
				<button type="button" class="btn btn-warning waves-effect text-white  width-md waves-light mx-1">
					PRINT
				</button>
			</div>
		</div>
	</div>

	<div class="row">
    	<div class="col-xl-12">
            <div class="card-box">

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">Product Table</h4>

                {{-- sections content --}}
				<table id="product-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td></td>
						<td>Product Name</td>
						<td>Stock</td>
						<td>Unit Price</td>
						<td></td>
					</thead>
				</table>

            </div>
        </div><!-- end col -->
    </div>

@endsection

@section('modals')

	@can('add_product')
		{{-- ADD NEW PRODUCT MODAL --}}
	    <div id="add-new-product-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="add-new-product" aria-hidden="true" style="display: none;">
	        <div class="modal-dialog modal-lg" role="document">
	
	            <div class="modal-content">
	                <div class="modal-header">
	                     <h4 class="header-title">
	                    	<i class="fas fa-syringe"></i> Add New Product
	                    </h4>
	                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                </div>
	                <div class="modal-body">

	                    <form id="product-add-form" enctype="multipart/form-data" method="POST">
							@productForm([
								'inputClassName' => 'for-add-product',
								'saveBtnName' => 'SAVE NEW PRODUCT'
							])
						</form>


	                </div>
	            </div><!-- /.modal-content -->
	        </div><!-- /.modal-dialog -->
	    </div><!-- /.modal -->
	    {{-- END OF ADD PRODUCT MODAL --}}
	@endcan

    {{-- PRODUCT DETAILS --}}
    <div id="product-details-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="product-details" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                     <h4 class="header-title">
                    	<i class="fas fa-syringe"></i> Product Details
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" data-toggle="tab" aria-expanded="false">×</button>
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
                        <span class="d-none d-sm-block">Batch Number</span>            
                    </a>
                </li>
                @can('edit_product')
					<li class="nav-item">
	                    <a href="#tab-3" data-toggle="tab" aria-expanded="false" class="nav-link">
	                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
	                        <span class="d-none d-sm-block">Edit Details</span>            
	                    </a>
	                </li>
                @endcan
                @can('add_batch_number')
					<li class="nav-item">
	                    <a href="#tab-4" data-toggle="tab" aria-expanded="false" class="nav-link">
	                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
	                        <span class="d-none d-sm-block">Add Batch Number</span>            
	                    </a>
	                </li>
                @endcan
            </ul>
			
			{{-- TAB CONTENTS --}}
			<div class="tab-content">
                <div role="tabpanel" class="tab-pane fade show active" id="tab-1">
                	 {{-- display --}}
                </div>

                <div role="tabpanel" class="tab-pane fade" id="tab-2">
                    <div class="my-2">
						<table id="batch-no-table" class="table table-striped bg-white text-center" style="width: 100%;">
							<thead class="text-primary">
								<td>Batch No</td>
								<td>Expiry Date</td>
								<td>Quantity</td>
								<td>Unit Cost</td>
								<td></td>
							</thead>
						</table>
					</div>
                </div>

                @can('edit_product')
					<div role="tabpanel" class="tab-pane fade" id="tab-3">

						<form id="product-update-form" enctype="multipart/form-data">
							{{-- @method('PATCH') --}}
							@productForm([
								'inputClassName' => 'for-update-product',
								'saveBtnName' => 'SAVE CHANGES'
							])
						</form>

	                </div>
                @endcan

                @can('add_batch_number')
					<div role="tabpanel" class="tab-pane fade" id="tab-4">
                    
	                    <form id="batch-add-form" method="POST">
	                    	@csrf
	                    	@batchNoForm([
	                        	'inputClassName' => 'for-add-batch',
	                        	'saveBtnName' => 'SAVE BATCH NUMBER',
	                        	'btnAddSupplier' => false,
	                        ])
	                    </form>

	                </div>
                @endcan

            </div> <!-- end of tab content -->
        </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    {{-- END OF ADD PRODUCT MODAL --}}

	@can('edit_batch_number')
		<!--  Modal content for the above example -->
	    <div id="edit-batch-no-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h4 class="modal-title">Edit Batch Number</h4>
	                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                </div>
	                <div class="modal-body">

	                   <form id="batch-update-form">
	                   		@batchNoForm([
		                       	'inputClassName' => 'for-update-batch',
		                       	'saveBtnName' => 'SAVE CHANGES',
	                        	'btnAddSupplier' => false,
		                   	])
	                   </form>

	                </div>
	            </div><!-- /.modal-content -->
	        </div><!-- /.modal-dialog -->
	    </div><!-- /.modal -->
	@endcan

    @can('record_loss')
		<!--  Modal content for the above example -->
	    <div id="loss-product-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	        <div class="modal-dialog modal-md">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h4 class="modal-title">Record Loss Product</h4>
	                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                </div>
	                <div class="modal-body">
	                   <form id="loss-product-form" method="post">
	                   		
							<div class="row">
								<div class="col-md-12">
									<div class="card-box">

										<input type="hidden" name="batch_no_id" value="">

							            <div class="form-group">
							                <label>Loss Date</label>
							                <input type="date" name="loss_date" parsley-trigger="change" required
							                       placeholder="" class="form-control for-add-loss --date-today">
							            </div>

							            <div class="form-group">
							                <label>Loss Quantity</label>
							                <div class="input-group">
							                	<input type="number" name="quantity" parsley-trigger="change" required
							                       placeholder="--loss quantity--" class="form-control for-add-loss --dont-exceed-max">
							                       <div class="input-group-append">
							                    		<div class="input-group-text">
							                    			pcs.
							                    		</div>
							                       </div>
							                </div>
							            </div>
							            <div class="form-group">
							                <label>Reason of Loss</label>
							                <textarea name="reason" parsley-trigger="change" required id="" cols="10" rows="5" placeholder="--reason of loss--" class="form-control for-add-loss"></textarea>
							            </div>

							            <div class="form-group text-right mb-0">
							                <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
							                    RECORD
							                </button>
							                <button type="reset" class="btn btn-secondary waves-effect waves-light">
							                    CANCEL
							                </button>
							            </div>

							        </div>
								</div>
							</div>

	                   </form>
	                </div>
	            </div><!-- /.modal-content -->
	        </div><!-- /.modal-dialog -->
	    </div><!-- /.modal -->
    @endcan


    <!--  Modal content for the above example -->
    <div id="add-supplier-modal" class="modal modal-child fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Supplier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                   
					<form action="#" method="post">
						{{--  --}}
						@supplierForm([
	                    	'inputClassName' => 'for-add-supplier',
	                    	'saveBtnName' => 'SAVE SUPPLIER',
	                    ])
					</form>

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

	{{-- PLUGINS --}}
	{{-- daterangepicker --}}
    <script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}"></script>
	<!-- dropify js -->
	<script src="{{ asset('/assets/libs/dropify/dropify.min.js') }}"></script>
	<!-- form-upload init -->
	<script src="{{ asset('/assets/js/pages/form-fileupload.init.js') }}"></script>

	{{-- datatables --}}
	<script src="{{ asset('/js/datatables/product.js') }}" type="text/javascript"></script>
	{{-- allmost forms --}}
	<script src="{{ asset('/js/inventory/product.js') }}" type="text/javascript"></script>

	<script type="text/javascript">
	
		// EDIT BATCH NUMBER
		$('#edit-batch-no-modal').on('show.bs.modal', function(e){
			$('#product-details-modal').modal('hide');
		});
		$('#edit-batch-no-modal').on('hidden.bs.modal', function(e){
			$('#product-details-modal').modal('show');
		});
		
		// SUPPLIER MODAL
		$('#add-supplier-modal').on('show.bs.modal', function(e){
			$('#product-details-modal').modal('hide');
		});
		$('#add-supplier-modal').on('hidden.bs.modal', function(e){
			$('#product-details-modal').modal('show');
		});

		// LOSS  MODAL
		$('#loss-product-modal').on('show.bs.modal', function(e){
			$('#product-details-modal').modal('hide');
		});
		$('#loss-product-modal').on('hidden.bs.modal', function(e){
			$('#product-details-modal').modal('show');
		});


	</script>

@endsection