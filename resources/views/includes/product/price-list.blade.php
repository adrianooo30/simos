<div class="col-xl-4 col-md-6 text-center  my-1">
	<div class="card card-shadow">
		<div class="row card-body text-center">
			<div class="col-12">
				<img src="{{ $product['product_img'] ?? '/images/products/syringe.png' }}" class="img-thumbnail size-50"><br>
				<div class="name">
					<h5 class="text-primary lighter">{{ $product['product_name'] ?? 'Generic Name 10ml' }}</h5>
					<h6 class="text-muted">{{ $product['brand_name'] ?? 'Brand Name' }}</h6>
					<h5 class="text-primary">&#8369; {{ $product['unit_price_format'] ?? '10,001.00' }}</h5>
				</div>
			</div>

			<button type="submit" class="button pulse waves-effect wave-light">
				SEE MORE DETAILS
			</button>
		</div>
	</div>
</div>