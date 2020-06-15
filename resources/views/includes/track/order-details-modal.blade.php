<div class="row">
	<div class="col-xl-4">
		<div class="card-box">
            <div class="card">
                <img class="image-150 img-thumbnail" src="{{ $track_order['account']['profile_img'] }}">
            </div>
        </div>
	</div>

	<div class="col-xl-8">
		<div class="row">
			<div class="col-xl-6">
				<div class="card-box">

                    <h3 class="text-secondary lighter">{{ $track_order['account']['account_name'] }}</h3>
                    <sup class="text-muted">{{ $track_order['account']['type'] }}</sup>

					<h3 class="text-primary lighter">{!! $track_order->pesoFormat($track_order['total_cost']) !!}</h3>
					<sup class="text-muted">Total Cost of Order</sup>
					
            	</div>
			</div>

			<div class="col-xl-6">
				<div class="card-box">

					<h4 class="text-primary lighter">{{ $track_order['order_date'] }}</h4>
					<sup class="text-muted">
						<span class="ti-calendar"></span> Date of Order
					</sup>

					<h4 class="text-primary lighter">{{ $track_order['employee']['full_name'] }}</h4>
					<sup class="text-muted">
						<i class="ti-user"></i> Assigned Employee
					</sup>

            	</div>
			</div>
		</div>

		@isset($track_order['deliver_transaction'])
			<h3 class="my-0 lighter">
	        	<i class="ti-truck text-primary"></i> Delivery Details
	        </h3>

			<div class="row">
				<div class="col-xl-6">
					<div class="card-box mb-0">
                        <h3 class="text-primary lighter">{{ $sales_transaction['deliverTransaction']['receipt_no'] }}</h3>
                        <sub class="text-muted">
							<i class="ti-receipt text-primary"></i> Receipt No
                        </sub>

						<h3 class="lighter">{{ $sales_transaction['deliverTransaction']['delivery_date'] }}</h3>
						<sup class="text-muted">
							<span class="ti-calendar text-primary"></span> Delivery Date
						</sup>
                	</div>
				</div>

				<div class="col-xl-6">
					<div class="card-box mb-0">
						<h4 class="text-primary lighter">{{ $sales_transaction['deliverTransaction']['employee']['full_name'] }}</h4>
						<sup class="text-muted">
							<span class="ti-user text-primary"></span> Delivered By
						</sup>
                	</div>
				</div>
			</div>
		@endisset

	</div>
	


</div>