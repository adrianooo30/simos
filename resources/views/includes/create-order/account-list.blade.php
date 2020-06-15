<div class="row">
	{{-- account list --}}
	@foreach($accounts as $account)
		<div class="col-xl-4 col-md-6 my-2">
			<div class="card card-shadow">
				<div class="row card-body text-center">
					<div class="col-12">
						<img src="{{ $account['profile_img'] }}" class="img-thumbnail size-50"><br>
						<div class="name">
							<h4 class="text-primary lighter">{{ $account['account_name'] }}</h4>
							<h5>{{ $account['type'] }}</h5>
						</div>
						
	{{-- 					<h4 class="text-warning lighter">{{ $account['bill_format'] }}</h4>
						<h5>Balance</h5> --}}

						<button class="button pulse waves-effect wave-light --select-account-btn" data-account-id="{{ $account['id'] }}">Select Account</button>
					</div>
				</div>
			</div>
		</div>
	@endforeach
</div>

<div id="account-list-pagination" class="d-flex justify-content-center">
	{{ $accounts->links() }}
</div>