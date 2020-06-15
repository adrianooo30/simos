<div class="row">
	@forelse( $products as $product )
		<div class="--product-card col-xl-4 col-md-6 text-center  my-1">
				<div class="card card-shadow">
					<div class="row card-body text-center">
						<div class="col-12">
							<img src="{{ $product['product_img'] }}" class="img-thumbnail size-50"><br>
							<div class="name">
								<h5 class="text-primary lighter">{{ $product['product_name'] }}</h5>
								<h6 class="text-muted">{{ $product['brand_name'] }}</h6>
								<h5 class="text-primary">&#8369; {{ $product['unit_price_format'] }}</h5>
							</div>
						</div>
	
						@php
							if( empty(!$products_in_cart) ) {
								foreach($products_in_cart as $product_in_cart) {
									if($product['id'] == $product_in_cart['product_id']) {
										$updated_stock_while_ordering = $product['stock_in_order'] - ($product_in_cart['total_quantity']);
									}
								}
							}
						@endphp

							<div class="col-12">
								@isset($product['approriate_deal']['deal_name'])
									<div class="alert alert-danger">
										<strong>{{ $product['approriate_deal']['deal_name'] }}</strong>
									</div>
								@endisset
							@if( $product['stock_in_order'] > 0 )
								<h5 class="text-primary lighter  --total-stock --product-id-{{ $product['id'] }}-display">{{ $updated_stock_while_ordering ?? $product['stock_in_order'] }}</h5>
								<h6>Total Stock</h6>
							@endif
							</div>
					</div>
				</div>

				<div>
					@if( $product['stock_in_order'] > 0 )
						<form method="post" class="--add-product-to-cart-form">
							{{-- 0 ,1 --}}
							<input type="hidden" name="product_id" value="{{ $product['id'] }}">
							<input type="number" name="quantity" placeholder="--quantity to order--" class="form-control text-center --product-id-{{ $product['id'] }}-value --dont-exceed-max" min="1" max="{{ $updated_stock_while_ordering ?? $product['stock_in_order'] }}" autocomplete="off">

							{{-- 2, 3 --}}
							<input type="hidden" name="buy" value="{{ $product['deal']['buy'] ?? 0 }}">
							<input type="hidden" name="take" value="{{ $product['deal']['take'] ?? 0 }}">

							{{-- <input type="hidden" name="unit_price" value=""> --}}
							
							<button type="submit" class="button pulse waves-effect wave-light">
								<i class="ti-shopping-cart"></i> ADD TO CART
							</button>
						</form>
					@else
						<div class="alert alert-danger">
							<strong class="text-center">No Stock Available</strong>
						</div>
					@endif
				</div>
		</div>
	
		@empty
			<div class="--product-card col text-center  my-1">
				<div class="card card-shadow p-4">
					<h4 class="text-warning text-center">
						@if( strlen($search_text) == 0 )
							Sorry, there are no already set products for you.
						@else
							Sorry, no result in your search.
						@endif
					</h4>
				</div>
			</div>

	@endforelse
</div>

<div id="product-list-pagination" class="d-flex justify-content-center">
	{{ $products->links() }}
</div>