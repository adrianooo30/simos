<div class="row" id="--bills-html">

	@foreach( $order_transactions as $order_transaction )
		<div class="col-xl-6 col-l-6 col-md-6 mx-auto">
			<div class="card card-shadow  --order-transaction-card">
				<div class="card-body">
					<div class="row">
						<div class="col-3">
							<i class="ti-receipt text-primary font-50"></i>
						</div>
						<div class="col-6">
							<h3 class="text-primary mt-0">
								{{ $order_transaction->deliverTransaction['receipt_no'] }}
							</h3>
							<h6 class="text-danger">
								@if( !is_null($order_transaction['total_bill']) )
									{!! $order_transaction->pesoFormat($order_transaction['total_bill']) !!}
								@else
									{!! $order_transaction->pesoFormat($order_transaction['total_cost']) !!}
								@endisset
							</h6>
							<h6 class="text-muted">Total Bill</h6>
							
							<div class="--order-transaction-total-payment">
								{{--  --}}
							</div>
						</div>
						<div class="col-3 --order-transaction-actions">
							<input type="checkbox" id="checkbox-o-{{ $order_transaction['id'] }}" class="d-none --bills-checkbox-o" data-order-transaction-id="{{ $order_transaction['id'] }}">
							<label for="checkbox-o-{{ $order_transaction['id'] }}" class="btn btn-outline-success text-success bg-white  my-1 --order-transaction-label  waves-effect wave-light">
								<i class="ti-check-box  font-weight-bold font-14"></i>
							</label>
							<br>
							<button type="button" class="btn btn-primary waves-effect wave-light my-1 --order-medicine-payment-btn" data-toggle="modal" data-target="#order-medicines-modal" data-order-transaction-id="{{ $order_transaction['id'] }}" style="display: none;">
								<i class="mdi mdi-chevron-right  font-weight-bold font-14"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach

</div>