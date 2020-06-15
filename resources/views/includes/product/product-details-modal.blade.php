<div class="row">
	<div class="col-md-4">
		<div class="card-box">

            <div class="card">
                <img class="card-img-top img-thumbnail" src="{{ asset( $product['product_img'] ) }}" width="50" height="50">
            </div>

        </div>
	</div>

	<div class="col-md-8">
		<div class="card-box">
            <h3 class="text-secondary lighter">{{ $product['product_name'] }}</h3>
            <h4 class="text-muted lighter">{{ $product['brand_name'] }}</h4>
            <h4 class="text-muted lighter">{{ $product['weight_volume'] }}</h4>

			<h4 class="text-primary lighter">{!! $product->quantityFormat($product['stock']) !!}</h4>
			<sup class="text-muted">Total Stock</sup>

			<h4 class="text-danger lighter">{!! $product->quantityFormat($product['critical_quantity']) !!}</h4>
			<sup class="text-muted">Critical Stock</sup>

			<h4 class="text-primary lighter">{!! $product->pesoFormat($product['unit_price']) !!}</h4>
			<sup class="text-muted">Unit Price</sup>
			
			@if($product['current_holder'] != null)
				<h3 class="text-primary lighter">
					<i class="ti-user text-primary"></i>
					{{ $product->current_holder->full_name }}
				</h3>
				<sup class="text-muted">Current Holder</sup>
			@else
				<h3 class="text-warning lighter"> <i class="ti-user"></i> NO HOLDER</h3>
				<sup class="text-muted">Current Holder</sup>
			@endif
    	</div>
	</div>
</div>