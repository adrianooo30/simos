{{-- in account.blade.php --}}
@isset( $account_name )
	<div>
	    <h5 class="text-primary">{{ $account_name }}</h5>
	    <sup class="text-muted">{{ $type }}</sup>
	</div>
@endisset

{{-- in account.blade.php --}}
@isset( $full_name )
	<div>
	    <h5 class="text-primary">{{ $full_name }}</h5>
	    <sup class="text-muted">{{ $position['position_name'] }}</sup>
	</div>
@endisset

{{-- in account.blade.php --}}
@isset( $supplier_name )
	<div>
	    <h5 class="text-primary">{{ $supplier_name }}</h5>
	</div>
@endisset

{{-- hahahahahaha --}}

{{-- in all transaction --}}
@isset( $account )
	<div>
	    <h5 class="text-primary">{{ $account['account_name'] }}</h5>
	    <sup class="text-muted">{{ $account['type'] }}</sup>
	</div>
@endisset