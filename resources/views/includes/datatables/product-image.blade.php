{{-- in product.blade.php --}}
@isset( $product_img )
	<div align="center">
	    <img src="{{ $product_img }}" alt="product_img" class="image-50">
	</div>
@endisset

{{-- ordered medicine - all modal --}}
@isset( $product )
	<div align="center">
	    <img src="{{ $product['product_img'] }}" alt="product_img" class="image-50">
	</div>
@endisset