{{-- <div class="row">
	<div class="col-xl-12">
			
		<div class="card">
			<div class="card-body">
				<div class="row">
					<alert class="alert alert-success p-4">
						<strong>Successfully recorded new collection.</strong>
					</alert>
				</div>

				<div class="row">
						
					<div class="col-xl-6">
						<h1 class="text-primary lighter">{{ $collection_transaction['account']['account_name'] ?? 'Sample Account' }}</h1>
						<h6 class="text-muted bold">
							<i class="ti-user"></i> Account Name
						</h6>

						<h3 class="light-black lighter">{{ $collection_transaction['employee']['full_name'] ?? 'Mr. Employee Lastname' }}</h3>
						<h6 class="text-muted bold"><i class="ti-user"></i> Collected By</h6>
			
						<hr>

						<h3 class="light-black lighter">
							<button class="btn btn-primary waves-effect wave-length">
								{{ ucfirst( $collection_transaction['mode_of_payment'] ) ?? 'Cash of Cheque' }}
							</button>
						</h3>
						<h6 class="text-muted bold">Mode of Payment</h6>

					</div><br>
					<div class="col-xl-6">
						<h3 class="text-primary lighter">{{ strtoupper( $collection_transaction['receipt_no'] ) ?? 'DR1290' }}</h3>
						<h6 class="text-muted bold"><i class="ti-receipt"></i> Collection Receipt</h6>

						<h3 class="light-black lighter">{{ $collection_transaction['collection_date'] ?? 'Jan 30, 2020' }}</h3>
						<h6 class="text-muted bold"><i class="ti-calendar"></i> Collection Date</h6>

						<h3 class="text-primary lighter">{{ $collection_transaction['collected_amount_format'] ?? '12345,00' }}</h3>
						<h6 class="text-muted bold">
							<i class="ti-receipt"></i> Collected Amount
						</h6>

						<h3 class="text-warning lighter">{{ $collection_transaction['account_name']['balance_format'] ?? '12,345.00' }}</h3>
						<h6 class="text-muted bold"><i class="ti-receipt"></i> Balance</h6>
					</div>

					@if( $collection_transaction['mode_of_payment'] === 'cheque' )
						<div class="col-xl-6">
							<h3 class="text-primary">Cheque Details</h3>
							<hr>
							<div class="row">
								<div class="col-sm">
									<h3 class="light-black lighter">{{ $collection_transaction['cheque']['cheque_no'] ?? '12,345.00' }}</h3>
									<h6 class="text-muted bold">
										<i class="ti-clipboard"></i> Cheque Number
									</h6>
								</div>
								<div class="col-sm">
									<h3 class="light-black lighter">{{ $collection_transaction['cheque']['date_of_cheque'] ?? 'Jan. 30, 1998' }}</h3>
									<h6 class="text-muted bold">
										<i class="ti-calendar"></i> Date of Cheque
									</h6>

									<h3 class="light-black lighter">{{ $collection_transaction['cheque']['bank'] ?? 'Bangko de Pungal' }}</h3>
									<h6 class="text-muted bold">
										<i class="fa fa-bank"></i> Bank
									</h6>
								</div>
							</div>
						</div>
					@endif
				</div>

				<div class="row">
			    	<div class="col-xl-12">
			            <div class="card-box">

			                <h4 class="header-title text-primary mt-0 mb-3">Product Table</h4>

							<table id="paid-bills-table" class="table table-striped bg-white text-center" style="width: 100%;">
								<thead class="text-primary">
									<td>Receipt No</td>
									<td>Total Cost</td>
									<td>Amount Paid</td>
									<td>Balance</td>
								</thead>
								<tbody>
									@foreach($collection_transaction['order_transactions'] as $paidBills)
										<tr>{{ $paidBills['receipt_no']}}</tr>
									@endforeach
								</tbody>
							</table>

			            </div>
			        </div><!-- end col -->
			    </div>

			</div>
		</div>

	</div>
</div> --}}

<div class="card">
	<div class="card-body p-4">
		<img src="{{ asset('/images/') }}" alt="">
	</div>
</div>