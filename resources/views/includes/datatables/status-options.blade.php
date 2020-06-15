
<div class="d-flex">
	@if( $status !== 'Delivered' )
		<div class="mx-2" onclick="toggleStatBtns('stat-opt{{ $id }}')">
		   <button class="btn btn-icon btn-primary waves-effect wave-light">
				<i class="ti-layers"></i>
		   </button>

		   <div id="stat-opt{{ $id }}" class="stat-opt" title="Click again to disappear">
		   		@if( $status === 'Pending' || $status === 'Canceled' )
					<button class="option approve  wave-effect wave-light" title="Approved" onclick="statBadge({{ $id }}, 'Approved')">A</button>
				@endif

				@if( $status === 'Approved' || $status === 'Pending' )
					<button class="option cancel  wave-effect wave-light" title="Cancel" onclick="statBadge({{ $id }}, 'Canceled')">C</button>
				@endif

				@if( $status === 'Approved' || $status === 'Canceled' )
					<button class="option pending  wave-effect wave-light" title="Pending" onclick="statBadge({{ $id }}, 'Pending')">P</button>
				@endif

				@if( $status === 'Approved' )
					@can('deliver_order')
						<button class="option deliver  wave-effect wave-light" title="Deliver" onclick="statBadge({{ $id }}, 'Delivered')" data-toggle="modal" data-target="#delivery-modal">D</button>
					@endcan
				@endif
		   </div>
		</div>
	@endif

	<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#track-modal" onclick="orderDetails({{ $id }})">
        <i class="ti-shopping-cart"></i>
    </button>
</div>