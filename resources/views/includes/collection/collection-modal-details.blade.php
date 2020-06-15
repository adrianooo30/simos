<div class="row">
	<div class="col-xl-4">
		<div class="card-box">

            <div class="card">
                <img class="card-img-top img-fluid" src="{{ asset( $collection_transaction['account']['profile_img'] ) }}">
            </div>

        </div>
	</div>

	<div class="col-xl-8" id="--collection-details">
		
		{{-- 1st section --}}
		<div class="row">
			<div class="col-xl-6">
				<div class="card-box">
					{{-- display product details --}}
                    <h3 class="text-secondary lighter">{{ $collection_transaction['account']['account_name'] }}</h3>
                    <sub class="text-muted">{{ $collection_transaction['account']['type'] }}</sub>

					<h3 class="text-primary lighter">{!! $collection_transaction->pesoFormat($collection_transaction['total_collected_amount']) !!}</h3>
					<sup class="text-muted">Total Paid</sup>

					<div class="my-2">
						@switch($collection_transaction['mode_of_payment'])
							@case('cash')
								<button type="button" class="btn btn-primary btn-rounded width-md waves-effect waves-light">
									{{ ucfirst($collection_transaction['mode_of_payment']) }}
								</button>
							@break

							@case('cheque')
								<button type="button" class="btn btn-success btn-rounded width-md waves-effect waves-light">
									{{ ucfirst($collection_transaction['mode_of_payment']) }}
								</button>
							@break

						@endswitch
					</div>
				</div>
			</div>
			<div class="col-xl-6">
				<div class="card-box">
					<h4 class="text-primary lighter">{{ $collection_transaction['receipt_no'] }}</h4>
					<sup class="text-muted">
						<span class="ti-receipt"></span> Collection Receipt
					</sup>

					<h4 class="text-primary lighter">{{ $collection_transaction['collection_date'] }}</h4>
					<sup class="text-muted">
						<span class="ti-calendar"></span> Date of Collection
					</sup>

					<h4 class="text-primary lighter">{{ $collection_transaction['employee']['full_name'] }}</h4>
					<sup class="text-muted">
						<i class="ti-user"></i> Assigned Employee
					</sup>
				</div>
			</div>
		</div>
	
	@if( $collection_transaction['mode_of_payment'] === 'cheque' )
		<h3 class="my-0 lighter">
        	<i class="ti-truck text-primary"></i> Cheque Details
        </h3>
		
		<div class="row">
			<div class="col-xl-6">
				<div class="card-box">
					{{-- display product details --}}
                    <h3 class="text-secondary lighter">{{ $collection_transaction['cheque']['cheque_no'] }}</h3>
                    <sub class="text-muted">Cheque Number</sub>
				</div>
			</div>
			<div class="col-xl-6">
				<div class="card-box">
					<h4 class="text-primary lighter">{{ $collection_transaction['cheque']['date_of_cheque'] }}</h4>
					<sup class="text-muted">
						<span class="ti-receipt"></span> Date of Cheque
					</sup>

					<h4 class="text-primary lighter">{{ $collection_transaction['cheque']['bank'] }}</h4>
					<sup class="text-muted">
						<span class="fa fa-bank"></span> Bank
					</sup>
				</div>
			</div>
		</div>
	@endif
	
	@isset( $collection_transaction['deposit'] )
		<h3 class="my-0 lighter">
        	<i class="ti-truck text-primary"></i> Deposit Details
        </h3>
		
		<div class="row">
			<div class="col-xl-6">
				<div class="card-box">
					<h4 class="text-primary lighter">{{ $collection_transaction['deposit']['bank'] }}</h4>
					<sup class="text-muted">
						<span class="fa fa-bank"></span> Bank
					</sup>
				</div>
			</div>
			<div class="col-xl-6">
				<div class="card-box">
					<h4 class="text-primary lighter">{{ $collection_transaction['deposit']['date_of_deposit'] }}</h4>
					<sup class="text-muted">
						<span class="ti-calendar"></span> Date of Deposit
					</sup>

					<h4 class="text-primary lighter">{{ $collection_transaction['deposit']['employee']['full_name'] }}</h4>
					<sup class="text-muted">
						<i class="ti-user"></i> Deposit By
					</sup>
				</div>
			</div>
		</div>
	@endisset

	</div>
</div>