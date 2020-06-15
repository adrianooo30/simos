{{-- usage in account, employee and supplier --}}
@isset( $profile_img )
	<div align="center">
	    <img src="{{ $profile_img }}" alt="profile_img" class="image-50 img-thumbnail">
	</div>
@endisset

{{-- in all order transaction --}}
@isset( $account )
	<div align="center">
	    <img src="{{ $account['profile_img'] }}" alt="profile_img" class="image-50 img-thumbnail">
	</div>
@endisset