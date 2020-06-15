<div class="row">
	<div class="col-xl-4">
		<div class="card-box mb-0">
            <div class="card mb-0">
                <img class="image-150 img-thumbnail" src="{{ asset( $sales_transaction['account']['profile_img'] ) }}">
            </div>
        </div>
	</div>

	<div class="col-xl-8">
		<div class="row mx-auto" id="--sales-details">
			<div class="col-xl-6">
				<div class="card-box mb-0">
                    <h3 class="text-secondary lighter">{{ $sales_transaction['account']['account_name'] }}</h3>
                    <sub class="text-muted">{{ $sales_transaction['account']['type'] }}</sub>

					<h3 class="text-primary lighter">{!! $sales_transaction->pesoFormat($sales_transaction['total_cost']) !!}</h3>
					<sup class="text-muted">Total Cost of Order</sup>

{{-- 					@if( $sales_transaction['excess_payment'] > 0 )
						<h3 class="text-warning lighter">{{ $sales_transaction['excess_payment_format'] }}</h3>
						<sup class="text-muted">Excess Payment</sup>
					@endif --}}

					@isset($sales_transaction['total_bill'])
						<h3 class="text-danger lighter">{!!  $sales_transaction['total_bill'] < 0 ? $sales_transaction->pesoFormat(0) : $sales_transaction->pesoFormat($sales_transaction['total_bill']) !!}</h3>
						<sup class="text-muted">Total Bill</sup>
					@endisset
					
					@isset($sales_transaction['total_paid_amount'])
						<h3 class="text-success lighter">{!! $sales_transaction->pesoFormat($sales_transaction['total_paid_amount']) !!}</h3>
						<sup class="text-muted">Total Payment</sup>
					@endisset

					<div class="my-2">
						@switch( $sales_transaction['status'] )
							@case('Delivered')
								<button type="button" class="status-btn status-btn-danger waves-effect wave-light">
									Not Paid
								</button>
							@break

							@case('Partially Paid')
								<button type="button" class="status-btn status-btn-warning waves-effect wave-light">
									{{ $sales_transaction['status'] }}
								</button>
							@break

							@case('Fully Paid')
								<button type="button" class="status-btn status-btn-primary waves-effect wave-light">
									{{ $sales_transaction['status'] }}
								</button>
							@break
						@endswitch
					</div>
            	</div>
			</div>

			<div class="col-xl-6">
				<div class="card-box mb-0">

					<h4 class="text-primary lighter">{{ $sales_transaction['order_date'] }}</h4>
					<sup class="text-muted">
						<span class="ti-calendar"></span> Date of Order
					</sup>

					<h4 class="text-primary lighter">{{ $sales_transaction['employee']['full_name'] }}</h4>
					<sup class="text-muted">
						<i class="ti-user"></i> Assigned Employee
					</sup>

            	</div>
			</div>
		</div>

		<div>
			<h3 class="my-0 lighter">
            	<i class="ti-truck text-primary"></i> Delivery Details
            </h3>
			<div class="row">
				<div class="col-xl-6">
					<div class="card-box mb-0">
                        <h3 class="text-primary lighter">{{ $sales_transaction->deliverTransaction['receipt_no'] }}</h3>
                        <sub class="text-muted">
							<i class="ti-receipt text-primary"></i> Receipt No
                        </sub>

						<h3 class="lighter">{{ $sales_transaction->deliverTransaction['delivery_date'] }}</h3>
						<sup class="text-muted">
							<span class="ti-calendar text-primary"></span> Delivery Date
						</sup>
                	</div>
				</div>

				<div class="col-xl-6">
					<div class="card-box mb-0">
						<h4 class="text-primary lighter">{{ $sales_transaction->deliverTransaction['employee']['full_name'] }}</h4>
						<sup class="text-muted">
							<span class="ti-user text-primary"></span> Delivered By
						</sup>
                	</div>
				</div>
			</div>
		</div>

	</div>

</div>