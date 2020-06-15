@extends('layouts.app')
@section('title', 'Create Order')
@section('title-of-page')
	<i class="ti-shopping-cart"></i> <span>Create Order</span>
@endsection

@section('styles')
	
	<link rel="stylesheet" type="text/css" href="{{ asset('/css/create-order-style.css') }}">
    {{-- dropify --}}
    <link href="{{ asset('/assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
     <!-- Notification css (Toastr) -->
    <link href="{{ asset('/assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

	<div class="card-box p-0 py-2">
    
        <div id="alert-sections">
            {{-- alert one --}}
            {{-- <div class="alert alert-info my-2 mx-3 --alert-box">
                <div class="row">
                    <div class="col-xl-6">
                        <strong>You added (4) quantity.</strong> <br>
                        <strong>Generic Name 10ml - Brand Name</strong> <br>
                        <sub class="text-muted font-weight-bolder">Want to see the review?</sub>
                    </div>
                    <div class="col-xl-6">
                        <button class="btn btn-primary waves-effect wave-light float-right --reload-table-btn">
                            <i class="ti-shopping-cart"></i> REVIEW CART
                        </button>
                    </div>
                </div>
            </div> --}}
        </div>

        <div class="card-body">

            <h4 class="header-title text-primary mb-3"> Create New Order</h4>

            <div id="btnwizard">

                <ul class="nav nav-pills bg-light nav-justified form-wizard-header mb-2">
                    <li class="nav-item">
                        <a href="#tab-1" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                            <i class="mdi mdi-account-circle mr-1"></i>
                            <span class="d-none d-sm-inline">Select Account</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-2" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                            <i class="mdi mdi-face-profile mr-1"></i>
                            <span class="d-none d-sm-inline">Products</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-3" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                            <i class="mdi mdi-checkbox-marked-circle-outline mr-1"></i>
                            <span class="d-none d-sm-inline">Review Cart</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content mb-0 border-0 p-0">

                    <div class="tab-pane fade" id="tab-1">
						
						<div class="row my-2">
							<div class="col-md-6">

								<div class="form-group">
									<label for="" class="text-primary">Search Account</label>
									<div class="input-group">
							            <input type="search" name="search_account" class="form-control" placeholder="Search account here...">
							        </div><!-- input-group -->
								</div>

							</div>
							<div class="col-md-6">
								<button type="button" class="btn btn-primary waves-effect  width-md waves-light mx-1 float-right" data-toggle="modal" data-target="#add-customer-account-modal">
									ADD NEW ACCOUNT
								</button>
							</div>
						</div>

                		{{-- loading here --}}
                		<h3 id="account-loading" class="text-center text-muted lighter my-3" style="display: none;">
                			Loading accounts... Please wait.
                		</h3>
                        <div id="account-content">
                        	<div id="account-list">
                        		@accountList
                        	</div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-2">
						
						<div class="row my-2">
							<div class="col-md-6 my-2">
								
								<div class="form-group">
									<label for="" class="text-primary">Search Product</label>
									<div class="input-group">
							            <input type="search" name="search_product" class="form-control" placeholder="Search product here...">
							        </div><!-- input-group -->
								</div>
								
							</div>
						</div>

                    	{{-- laoding here --}}
						<h3 id="product-loading" class="text-center text-muted lighter my-3">
                			Loading products... Please wait.
                		</h3>
                        <div id="product-content">
                        	<div id="product-list">
								{{-- @productList --}}
                        	</div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-3">
                        <div class="row">
                            	
                            @reviewOrder

                        </div> <!-- end row -->
                    </div>
                
                    {{-- <div class="float-right">
                        <input type='button' class='btn btn-secondary button-next' name='next' value='Next' />
                        <input type='button' class='btn btn-secondary button-last' name='last' value='Last' />
                    </div>
                    <div class="float-left">
                        <input type='button' class='btn btn-secondary button-first' name='first' value='First' />
                        <input type='button' class='btn btn-secondary button-previous' name='previous' value='Previous' />
                    </div> --}}

                    <div class="clearfix"></div>

                </div> <!-- tab-content -->
            </div> <!-- end #btnwizard-->

        </div> <!-- end card-body -->
    </div> <!-- end card-->

@endsection

@section('modals')

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

@endsection

@section('scripts')

    {{-- PLUGIN JS --}}
	<script src="{{ asset('/assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <!-- Init js-->
    <script src="{{ asset('/assets/js/pages/form-wizard.init.js') }}"></script>
    <!-- Toastr js -->
    <script src="{{ asset('/assets/libs/toastr/toastr.min.js') }}"></script>
    <!-- dropify js -->
    <script src="{{ asset('/assets/libs/dropify/dropify.min.js') }}"></script>
    <!-- dropify js -->
	<script src="{{ asset('/assets/libs/dropify/dropify.min.js') }}"></script>
	<!-- form-upload init -->
	<script src="{{ asset('/assets/js/pages/form-fileupload.init.js') }}"></script>
    
    <script>
        var currentlyDisplayedAccounts = @json($accounts);
    </script>
    <script src="{{ asset('/js/order/create.js') }}" type="text/javascript"></script>

@endsection