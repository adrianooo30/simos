<div class="row">
	<div class="col-xl-4">
		<div class="card-box">
            <div class="card">
                <img class="image-150 img-thumbnail" src="{{ asset( $pending_order['account']['profile_img'] ) }}">
            </div>
        </div>
	</div>

	{{-- {{ $pending_order['account']['account'] }} --}}

	<div class="col-xl-8">
		<div class="row">
			<div class="col-xl-6">
				<div class="card-box">

                    <h3 class="text-secondary lighter">{{ $pending_order['account']['account_name'] }}</h3>
                    <sup class="text-muted">
                    	<i class="ti-home"></i>
                    	{{ $pending_order['account']['type'] }}
                    </sup>

					<h3 class="text-primary lighter">{!! $pending_order->pesoFormat($pending_order['total_cost']) !!}</h3>
					<sup class="text-muted">Total Cost of Order</sup>
					
            	</div>
			</div>

			<div class="col-xl-6">
				<div class="card-box">

					<h4 class="text-primary lighter">{{ $pending_order['order_date'] }}</h4>
					<sup class="text-muted">
						<span class="ti-calendar"></span> Date of Order
					</sup>

					<h4 class="text-primary lighter">{{ $pending_order['employee']['full_name'] }}</h4>
					<sup class="text-muted">
						<i class="ti-user"></i> Assigned Employee
					</sup>

            	</div>
			</div>
		</div>
	</div>
</div>