@extends('layouts.app')
@section('title', 'Accounts Receivable')
@section('title-of-page')
	<i class="ti-receipt"></i> <span>Accounts Receivable <span class="blue"><i class="ti-angle-right"></i></span> {{ $account['account_name']}}</span>
@endsection

{{-- style --}}
@section('styles')
	
	{{-- datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}"/>
	{{-- responsive datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.css') }}">

    {{-- <link href="{{ asset('/plugins/switchery/standalone/switchery.css') }}" rel="stylesheet" /> --}}

@endsection

@section('content')		
	
	<div class="card-box p-0 my-3" id="--receivable-content">

		<form id="receivables-form" method="POST">
			<div class="card">
				<div class="card-body">
						
					<div class="row text-center my-3">
						<div class="col-xl-4">
							<img src="{{ $account['profile_img'] }}" alt="" class="img-thumbnail image-150">
						</div>
						<div class="col-xl-4">
							<h3 class="text-primary">{{ $account['account_name'] }}</h3>
							<sup><i class="ti-home"></i> {{ $account['type'] }}</sup>
							<h3 class="text-warning"> {!! $account->pesoFormat( $account['total_bill'] ) !!}</h3>
							<sup><i class="ti-wallet"></i> Total Bill</sup>
						</div>
					</div>

					<div class="row">
						<div class="col-xl-6 mx-auto">
							
							<h5 class="text-primary">Collection Details</h5>

							@csrf
							<input type="hidden" name="employee_id" value="{{ Auth::id() }}">

							<div class="form-group">
								<label for="">Receipt</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<div class="input-group-addon">
											<select name="receipt_type" id="" class="form-control for-payment" required>
												<option>CR</option>
												<option>SI</option>
											</select>
										</div>
									</div>
									<input type="number" name="receipt_no" class="form-control for-payment" placeholder="--receipt number--">
								</div>
							</div>

							<div class="form-group">
								<label for="">Collection Date</label>
								<div class="input-group">
									<input type="date" name="collection_date" class="form-control for-payment --date-today">
								</div>
							</div>

							<div class="form-group">
								<label for="">Mode of Payment</label>
								<div class="input-group">
									<select name="mode_of_payment" onchange="modeOfPayment(this.value, 'cheque-1')" class="form-control for-payment">
										<option value="cash">Cash</option>
										<option value="cheque">Cheque</option>
									</select>
								</div>
							</div>
						</div>

						<div class="col-xl-6 max-auto" id="--cheque-details" style="display: none;">
							<h4 class="text-primary">Cheque Details</h4>
							<div class="form-group">
								<label>Cheque Number</label>
								<div class="input-group">
									<input type="number" name="cheque_no" placeholder="Cheque Number" class="form-control for-payment">
								</div>
							</div>
							<div class="form-group">
								<label>Date of Cheque</label>
								<div class="input-group">
									<input type="date" name="date_of_cheque" class="form-control for-payment">
								</div>
							</div>
							<div class="form-group">
								<label>Bank</label>
								<div class="input-group">
									<input type="text" name="bank" placeholder="Bank" class="form-control for-payment">
								</div>
							</div>
						</div>
					</div>

					<hr>

					<div class="my-3" id="bills-list">
						@billsList
					</div>

					<div class="row">
						<div class="col-xl-12 d-flex justify-content-center">
							<button type="submit" class="btn btn-primary waves-effect wave-light">
								SAVE COLLECTIONS
							</button>
						</div>
					</div>
				
				</div>
			</div>
		</form>

	</div>

@endsection

@section('modals')
	
	{{-- ADD NEW PRODUCT MODAL --}}
	<div id="order-medicines-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="order-medicines" aria-hidden="true" style="display: none;">
	    <div class="modal-dialog modal-lg" role="document">
	        <div class="modal-content">

	            <div class="modal-header">
	                <h3 class="header-title">
	                	<i class="ti-receipt text-primary"></i> <span id="--receipt-no"></span> 
	                </h3>
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	            </div>
	            <div class="modal-body">
					{{--  --}}
					<div id="order-medicines-payables-loading" style="display: none;">
						<h3 class="text-muted text-center font-weight-lighter">
							<div class="spinner-grow text-custom d-inline mx-2" role="status">
                                <span class="sr-only">Loading...</span>
                            </div> Loading... Please wait.
						</h3>
					</div>
					<div id="order-medicines-payables-html"></div>
	            </div>

	            <div class="modal-footer">
					{{-- <div class="float-right">
						<div class="text-right">
						    <button class="btn btn-primary waves-effect wave-light  --save-payables-btn">SAVE PAYABLES</button><br>
						    <sub class="text-muted">Save collection of per products for this receipt number.</sub>
						</div>
					</div> --}}
	            </div>

	        </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	{{-- END OF ADD PRODUCT MODAL --}}

@endsection

@section('scripts')

	<script type="text/javascript">
		var accountId = {{$account['id']}};
	</script>

    {{-- <script src="{{ asset('/plugins/switchery/standalone/switchery.js') }}"></script> --}}

	{{-- plugin datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
	
	{{-- responsive datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.js') }}"></script>

	{{-- datatables --}}
	<script type="text/javascript" src="{{ asset('js/datatables/receivables-show.js') }}"></script>

	<script src="{{asset('js/receivable/show.js')}}" type="text/javascript"></script>

@endsection