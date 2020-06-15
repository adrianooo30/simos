

{{-- in account.blade.php --}}
@isset( $account_name )
	<div class="d-flex">
		<a href="/account/history/{{ $id }}" class="btn btn-primary waves-effect waves-light mx-1">
			<i class="ti-bookmark-alt"></i>
		</a>
		<button class="btn btn-warning waves-effect waves-light mx-1" data-toggle="modal" data-target="#edit-customer-account-modal" onclick="userDetails({{ $id }})">
			<i class="ti-pencil-alt"></i>
		</button>
	</div>
@endisset

{{-- in employee.blade.php --}}
@isset( $full_name )
	{{--  --}}
@endisset

{{-- in supplier.blade.php --}}
@isset( $supplier_name )
	<div class="d-flex">
		<a href="#" class="btn btn-primary  waves-effect waves-light mx-1">
			<i class="ti-bookmark-alt"></i>
		</a>
		<button class="btn btn-warning waves-effect waves-light mx-1" data-toggle="modal" data-target="#edit-supplier-modal" onclick="userDetails({{ $id }})">
			<i class="ti-pencil-alt"></i>
		</button>
	</div>
@endisset

{{--  --}}
@isset( $account )
	<div class="d-flex" align="center">
		<button class="btn btn-primary waves-effect waves-light mx-1" data-toggle="modal" data-target="#add-account-modal">
			<i class="ti-shopping-cart"></i>
		</button>
	</div>
@endisset