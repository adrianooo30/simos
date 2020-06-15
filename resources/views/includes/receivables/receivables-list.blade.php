<div class="row">
	{{-- account list --}}
	@forelse($accounts as $account)
		<div class="col-xl-4 col-md-6 my-2 mx-auto">
			<div class="card card-shadow">
				<div class="row card-body text-center">
					<div class="col-12">
						<img src="{{ $account['profile_img'] }}" class="img-thumbnail size-50"><br>
						<div class="name">
							<h5 class="text-primary">{{ $account['account_name'] }}</h5>
							<h6>{{ $account['type'] }}</h6>
						</div>
						
						<h5 class="text-danger">{!! $account->pesoFormat($account['total_bill']) !!}</h5>
						<h6>Balance</h6>

						<a href="{{ route('transactions.receivables.show', [ 'account' => $account['id'] ]) }}" class="button pulse waves-effect wave-length  text-white">
							<i class="ti-user"></i> SELECT ACCOUNT
						</a>
					</div>
				</div>
			</div>
		</div>
	@empty
		<div class="col-xl-8 mx-auto">
			<div class="card card-shadow">
				<div class="card-body">
					<h4 class="text-primary text-center">
						There are no accounts that has bills.
					</h4>
				</div>
			</div>
		</div>

	@endforelse

</div>

<div id="receivables-list-pagination" class="d-flex justify-content-center">
	{{ $accounts->links() }}
</div>