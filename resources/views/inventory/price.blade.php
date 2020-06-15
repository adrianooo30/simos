@extends('layouts.app')
@section('title', 'Price Management')
@section('title-of-page')
	<i class="ti-direction fa-lg"></i> <span>Price Management</span>
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
				{{--  --}}
			</div>
			<div class="col-xl-2"></div>
			<div class="col-xl-6">
				<button type="button" class="btn btn-primary waves-effect  float-right  width-md waves-light mx-1" data-toggle="modal" data-target="#add-new-price-modal">
					ADD NEW PRICE
				</button>
			</div>
		</div>
	</div>

	<div class="row">
    	<div class="col-xl-12">
            <div class="card-box">

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">Table of Unique Prices</h4>

                {{-- sections content --}}
               <table id="price-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td>Product Name</td>
						<td>Unit Price</td>
						<td></td>
					</thead>
				</table>

            </div>
        </div><!-- end col -->

    </div>

@endsection

@section('modals')

	<!--  Modal content for the above example -->
	<div id="add-new-price-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	    <div class="modal-dialog modal-md">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h4 class="modal-title text-primary">Add New Price</h4>
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	            </div>
	            <div class="modal-body">
	                
					<form method="POST" id="price-add-form">
						@csrf
						<div class="row">

							<div class="col-10 mx-auto">
								<div class="form-group">
									<label class="text-primary">
										<i class="fas fa-syringe"></i> SELECT PRODUCT
									</label>
									<select name="product_id" class="--select-box-products for-add-price form-control">
										{{--  --}}
									</select>
								</div>

								<div class="form-group">
									<div class="row text-center">
										<div class="col-xl-12" id="--unit-price-html">
											{{--  --}}
										</div>
									</div>
								</div>

								<div class="form-group">
									<label for="" class="text-primary">UNIT PRICE</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text">
												&#8369;
											</div>
										</div>
										<input type="text" name="unit_price" class="form-control for-add-price text-center --must-currency" id="--price" placeholder="--set unique unit price--">
									</div>

								</div>
							</div>
							
							<div class="col-10 mx-auto">
								<hr>
								<div>
									<div class="form-group">
										<label class="text-primary imp">
											<i class="ti-user"></i> ADD CUSTOMER ACCOUNT
										</label>
										<div class="input-group">
											<select name="account_id" class="--select-box-accounts for-add-price form-control"></select>
										</div>
									</div>
								</div>

								<h4 class="text-primary lighter my-4">
									<i class="fas fa-syringe"></i> List of Accounts in Price
								</h4>
								
								<input type="hidden" name="no_selected" class="for-add-price" value="true">
								<ul class="list-group --added-account-in-price for-add-price" style="max-height: 450px; overflow-y: auto; overflow-x: hidden;">
									<li class="list-group-item text-muted text-center card-shadow">
								    	No account list in price yet.
								    </li>
								</ul>

								<div class="input-box">
									<div class="input-field">
										<input type="hidden" name="accountIds" class="for-add-price">
									</div>
								</div>

								{{--  --}}
								<div class="d-flex justify-content-center my-4">

	                                <button type="submit" class="btn btn-primary waves-effect waves-light">
										<i class="ti-write"></i>
	                                	<span>SAVE PRICE</span>
	                                </button>

								</div>
								{{--  --}}	
							</div>

						</div>

					</form>

	            </div>
	        </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


	<!--  Modal content for the above example -->
	    <div id="edit-price-modal" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	        <div class="modal-dialog modal-md">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h4 class="modal-title">Edit Deal</h4>
	                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                </div>
	                <div class="modal-body">

	                	{{-- start of content --}}
					<form method="POST" id="price-edit-form">

						<div class="row">
							<div class="col-xl-10 mx-auto">
								
								<div id="--product-details">
									{{--  --}}
								</div>

								<div class="form-group">
									<label for="" class="text-primary">UNIT PRICE</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text">
												&#8369;
											</div>
										</div>
										<input type="text" name="unit_price" class="form-control for-edit-price text-center" id="--edit-unit-price" placeholder="--set unique unit price--">
									</div>
								</div>

							</div>

							<div class="col-xl-10 mx-auto">

								<div class="form-group">
									<label for="" class="text-primary">
										<i class="ti-user"></i> ADD CUSTOMER ACCOUNT
									</label>
									<div class="input-group">
										<select name="account_id" id="" class="form-control for-edit-price --select-box-accounts">
										{{-- option --}}
										</select>
									</div>
								</div>
													
								<h4 class="text-primary lighter my-4">
									<i class="fas fa-syringe"></i> List of Accounts in Promo
								</h4>
								
								<input type="hidden" name="no_selected" class="for-edit-price" value="false"> 
							
								<ul class="list-group --added-account-in-price for-edit-price">
								    <li class="list-group-item text-muted text-center">
								    	No account list in deal yet.
								    </li>
								</ul>
								
								<input type="hidden" name="accountIds" class="for-edit-price">

								<div class="d-flex justify-content-center my-4">
									<div class="d-flex">
										<button type="submit" class="btn btn-primary waves-effect waves-light mx-2">
											<i class="ti-write"></i> SAVE CHANGES
										</button>
									</div>
								</div>
							
							</div>
						</div>

					</form>
					{{-- end of content --}}

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

	<script type="text/javascript" src="{{ asset('/js/datatables/price.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/inventory/price.js') }}"></script>

@endsection