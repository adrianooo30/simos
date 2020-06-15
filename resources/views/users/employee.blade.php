@extends('layouts.app')
@section('title', 'Employee')
@section('title-of-page')
	<i class="ti-user"></i> <span> Employee</span>
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
				@can('add_employee')
					<button type="button" class="btn btn-primary waves-effect  width-md waves-light mx-1" data-toggle="modal" data-target="#add-employee-modal">
						ADD EMPLOYEE
					</button>
				@endcan
			</div>
		</div>
	</div>

	<div class="row">
    	<div class="col-xl-12">
            <div class="card-box">

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">Employees Table</h4>

                {{-- sections content --}}
				<table id="employee-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td>Full Name</td>
						<td>Address</td>
						<td>Contact</td>
						<td></td>
					</thead>
				</table>

            </div>
        </div><!-- end col -->
    </div>
    
    <div class="row">
		@can('view_employee_qoutas')
			<div class="col-xl-12" id="psr-qoutas">
		    	@qoutasDisplay
		    </div>
		@endcan
	</div>
@endsection

@section('modals')

	@can('add_employee')
		<!--  Modal content for the above example -->
	    <div id="add-employee-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h4 class="modal-title">Add Employee</h4>
	                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                </div>
	                <div class="modal-body">
	                   	
	                    <form id="employee-add-form" enctype="multipart/form-data">
	                    	@employeeForm([
		                    	'inputClassName' => 'for-add-employee',
		                    	'saveBtnName' => 'SAVE ACCOUNT',
		                    ])
	                    </form>

	                </div>
	            </div><!-- /.modal-content -->
	        </div><!-- /.modal-dialog -->
	    </div><!-- /.modal -->
	@endcan
	
	@can('edit_employee')
		<!--  Modal content for the above example -->
	    <div id="edit-employee-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h4 class="modal-title">Edit Employee</h4>
	                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                </div>
	                <div class="modal-body">
	                   	
	                    <form id="employee-update-form" enctype="multipart/form-data">
	                    	@employeeForm([
		                    	'inputClassName' => 'for-update-employee',
		                    	'saveBtnName' => 'SAVE CHANGES',
		                    ])
	                    </form>

	                </div>
	            </div><!-- /.modal-content -->
	        </div><!-- /.modal-dialog -->
	    </div><!-- /.modal -->
	@endcan
	
	@can('hold_product')
		<!--  Modal content for the above example -->
    <div id="set-product-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Set Products</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                   	
                    <div class="row">
						<div class="col-xl-10  text-center mx-auto">
                           
                        <form id="--set-product-form">
                        	@csrf
                            <div class="card-box">
                            	<div class="card">
	                                <img class="card-img-top img-thumbnail mx-auto image-200 for-set-product" src="{{ asset('/assets/images/users/user-1.jpg') }}" alt="user image" id="--profile-img">
	                                <div class="card-body p-0">

										<h4 class="text-primary lighter" id="--full-name"></h4>
										<sup class="text-muted" id="--position"></sup>

	                                </div>
	                            </div>
								
								<div class="form-group">
									<label for="" class="text-primary">Target Amount</label>
									<div class="input-group">
										<div class="input-group-prepend">
					                        <span class="input-group-text">&#8369;</span>
					                    </div>
										<input type="number" name="target_amount" placeholder="--target amount--" class="form-control text-center for-set-product" id="--target-amount" required>
					                </div><!-- input-group -->
								</div>

								<div class="">
									<div id="--info-for-target-amount">
										<sub class="text-primary">Change the target amount.</sub>
									</div>

									<div class="my-2 --btns-for-target-amount">
										<button type="button" class="btn btn-warning mx-1 waves-effect waves-light" title="Edit target amount." id="--btn-edit-target">
											<i class="ti-pencil-alt"></i>
										</button>
										<button type="button" class="btn btn-primary mx-1 waves-effect waves-light" title="Change target amount for the current start date." id="--btn-change-target">
											<i class="ti-write"></i>
										</button>
										<button type="button" class="btn btn-danger mx-1 waves-effect waves-light" title="Cancel the edit and change process." id="--btn-cancel-target" style="display: none">
											<i class="ti-close"></i>
										</button>
									</div>
								</div>

								{{--  --}}
							{{-- LIST OF SELECTED PRODUCTS --}}

								<div class="my-2">
																	
									<div class="form-group">
										<label class="text-primary imp">
											<i class="fas fa-syringe"></i> ADD PRODUCTS TO HOLD
										</label>
										<div class="input-group">
											<select name="product_id" class="form-control for-set-product" id="--select-box-product" onchange="addProductToBeHold(this.value)">
											</select>
										</div>
									</div>

									<hr>

									<div class="my-4">
										<label class="text-primary imp">
											<i class="fas fa-syringe"></i> LIST OF SELECTED PRODUCTS
										</label>

										<ul class="list-group my-2 for-set-product" id="--added-product-list" style="max-height: 450px; overflow-y: auto; overflow-x: hidden;">
											{{-- list of product holded --}}
											<li class="list-group-item text-muted text-center card-shadow" id="--product-list-none">
										    	No product currently holding yet.
										    </li>
										</ul>
									</div>

								</div>
			
								<div class="form-group mb-0">
					                <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
					                    SAVE CHANGES
					                </button>{{-- 
					                <button type="reset" class="btn btn-secondary waves-effect waves-light">
					                    CANCEL
					                </button> --}}
					            </div>
                            </div>
                        </form>

						</div>

					</div>

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
	<script type="text/javascript" src="{{ asset('js/datatables/employee.js') }}"></script>

	<script src="{{asset('js/users/employee.js')}}" type="text/javascript"></script>

@endsection