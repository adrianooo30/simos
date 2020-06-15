@extends('layouts.app')
@section('title', 'Deals Management')
@section('title-of-page')
	<i class="fas fa-syringe"></i> <span>Deals Management</span>
@endsection

{{-- style --}}
@section('styles')
	
	{{-- datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}"/>
	{{-- responsive datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.css') }}">

@endsection

@section('content')

	<div class="card my-3">
		<div class="card-body row p-2">
			<div class="col-xl-4">
				{{--  --}}
			</div>
			<div class="col-xl-2">
				{{--  --}}
			</div>
			<div class="col-xl-6">
				@can('add_deals')
					<button type="button" class="btn btn-bordred-primary waves-effect  width-md waves-light float-right text-uppercase" data-toggle="modal" data-target="#add-new-deals-modal">
						Add New Deals
					</button>
				@endcan
			</div>
		</div>
	</div>

	<div class="card-box my-3">
		
		<h4 class="header-title text-primary mt-0 mb-3">Table of Deals</h4>

		{{-- sections content --}}
        <div class="m-4">
			<table id="deals-table" class="table table-striped bg-white text-center" style="width: 100%;">
				<thead class="text-primary">
					<td></td>
					<td>Product Name</td>
					<td>Deals</td>
					<td></td>
				</thead>
			</table>
		</div>

	</div>

@endsection

@section('modals')

	@can('add_deals')
		<!--  Modal content for the above example -->
		<div id="add-new-deals-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
		    <div class="modal-dialog modal-md">
		        <div class="modal-content">
		            <div class="modal-header">
		                <h4 class="modal-title text-primary">Add New Deal</h4>
		                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		            </div>
		            <div class="modal-body">
		                
						<form method="POST" id="promo-add-form">
							@csrf
							<div class="row">

								<div class="col-10 mx-auto">
									<div>
										<h5 class="text-info">Step 1</h5>
										<label class="text-primary">
											<i class="fas fa-syringe"></i> Product Name
										</label>

										<div class="form-group">
											<select name="product_id" class="--select-box-products for-add-promo form-control">
												{{--  --}}
											</select>
										</div>

									</div>
									<hr>
									<div>
										<h5 class="text-info">Step 2</h5>

										<h6 for="" class="text-primary"> Deal Name</h6>
										
										<div class="form-group">
											<label for="" class="text-primary">Buy</label>
											<div class="input-group">
												<input type="number" name="buy" placeholder="--quantity to buy--" class="for-add-promo form-control text-center">
											    <div class="input-group-append">
											        <span class="input-group-text">
											        	pcs.
											        </span>
											    </div>
											</div><!-- input-group -->
										</div>
										
										<div class="form-group">
											<label for="" class="text-primary">Take</label>
											<div class="input-group">
												<input type="number" name="take" placeholder="--quantity to take--" class="for-add-promo form-control text-center">
											    <div class="input-group-append">
											        <span class="input-group-text">
											        	pcs.
											        </span>
											    </div>
											</div><!-- input-group -->
										</div>
									</div>	
								</div>
								
								<div class="col-10 mx-auto">
									<hr>
									<div>
										<h5 class="text-info">Step 3</h5>

										<div class="form-group">
											<label class="text-primary imp">
												<i class="ti-user"></i> Add Customer Account
											</label>
											<div class="input-group">
												<select name="account_id" class="--select-box-accounts for-add-promo form-control"></select>
											</div>
											{{-- <span class="text-danger h6 font-12">This field is required.</span> --}}
										</div>
										{{-- <div class="text-center">
											<button type="button" class="btn btn-outline-primary my-2" onclick="addAccountToPromo()">
												<i class="fas fa-user-plus"></i> Add Account in Promo
											</button>
										</div> --}}
									</div>

									<h4 class="text-primary lighter my-4">
										<i class="fas fa-syringe"></i> List of Accounts in Deals
									</h4>
									
									<input type="hidden" name="no_selected" class="for-add-promo" value="true">
									<ul class="list-group --added-account-in-promo for-add-promo" style="max-height: 450px; overflow-y: auto; overflow-x: hidden;">
										<li class="list-group-item text-muted text-center card-shadow">
									    	No account list in deal yet.
									    </li>
									</ul>

									<div class="input-box">
										<div class="input-field">
											<input type="hidden" name="accountIds" class="for-add-promo">
										</div>
									</div>

									{{--  --}}
									<div class="d-flex justify-content-center my-4">

	                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
											<i class="ti-write"></i>
	                                    	<span>SAVE DEAL</span>
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
	@endcan

	@can('edit_deals')
		<!--  Modal content for the above example -->
	    <div id="edit-deals-modal" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	        <div class="modal-dialog modal-md">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h4 class="modal-title">Edit Deal</h4>
	                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                </div>
	                <div class="modal-body">

	                	{{-- start of content --}}
					<form method="POST" id="promo-edit-form">

						<div class="row">
							<div class="col-xl-10 mx-auto">
								
								<div id="--product-details">
									{{--  --}}
								</div>

						    	{{-- <h4 class="text-primary text-center" id="--promo-name"></h4> --}}

								<div class="form-group">
									<label for="" class="text-primary">BUY</label>
									<div class="input-group">
										<input type="text" name="buy" class="form-control for-edit-promo text-center" id="--buy">
										<div class="input-group-append">
											<div class="input-group-text">
												pcs.
											</div>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label for="" class="text-primary">TAKE</label>
									<div class="input-group">
										<input type="text" name="take" class="form-control for-edit-promo text-center" id="--take">
										<div class="input-group-append">
											<div class="input-group-text">
												pcs.
											</div>
										</div>
									</div>
								</div>

							</div>

							<div class="col-xl-10 mx-auto">

								<div class="form-group">
									<label for="" class="text-primary">
										<i class="ti-user"></i> ADD CUSTOMER ACCOUNT
									</label>
									<div class="input-group">
										<select name="account_id" id="" class="form-control for-edit-promo --select-box-accounts">
										{{-- option --}}
										</select>
									</div>
								</div>
													
								<h4 class="text-primary lighter my-4">
									<i class="fas fa-syringe"></i> List of Accounts in Promo
								</h4>
								
								<input type="hidden" name="no_selected" class="for-edit-promo" value="false"> 
							
								<ul class="list-group --added-account-in-promo for-edit-promo">
								    <li class="list-group-item text-muted text-center">
								    	No account list in deal yet.
								    </li>
								</ul>
								
								<input type="hidden" name="accountIds" class="for-edit-promo">

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
	@endcan

@endsection

@section('scripts')

	{{-- plugin datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
	
	{{-- responsive datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.js') }}"></script>
	
	{{-- custom js --}}
	<script src="{{ asset('/js/datatables/deals.js') }}" type="text/javascript"></script>
	<script src="{{asset('js/inventory/deals.js')}}" type="text/javascript"></script>

@endsection