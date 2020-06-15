<div class="name">
	<h6 class="lighter">
		<i class="ti-mobile text-primary"></i> {{ $contact_no }}
	</h6>
	
	@isset( $contact_person )
		<sup> <i class="ti-user text-primary"></i> {{ $contact_person }} </sup>
	@endisset
</div>