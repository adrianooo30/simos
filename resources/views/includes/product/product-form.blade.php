<div class="row">
	<div class="col-md-6">
		<div class="card-box">

            <h4 class="header-title mt-0 mb-3">Select Custom Image Here</h4>

            <input type="hidden" name="product_img_hidden" value="/images/products/syringe.png" class="{{ $inputClassName }}">

            <input type="file" name="product_img" class="dropify {{ $inputClassName }}" data-default-file="{{ asset('/images/products/syringe.png') }}" />

        </div>
	</div>

	<div class="col-md-6">
		<div class="card-box">
            @csrf
            <div class="form-group">
                <label>Generic Name</label>
                <input type="text" name="generic_name" parsley-trigger="change" 
                       placeholder="Generic Name" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Brand Name</label>
                <input type="text" name="brand_name" parsley-trigger="change" 
                       placeholder="Brand Name" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Product Unit</label>
                <input type="text" name="product_unit" parsley-trigger="change" 
                       placeholder="Product Unit" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Weight / Volume</label>
                <input type="text" name="weight_volume" parsley-trigger="change" 
                       placeholder="Weight / Volume" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Strength</label>
                <input type="text" name="strength" parsley-trigger="change" 
                       placeholder="Strength" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Unit Price</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            &#8369;
                        </div>
                    </div>
                    <input type="number" name="unit_price" parsley-trigger="change" 
                       placeholder="Unit Price" class="form-control {{ $inputClassName }}">
                </div>
            </div>

            <div class="form-group">
                <label>Critical Quantity</label>
                <div class="input-group">
                    <input type="number" name="critical_quantity" parsley-trigger="change" 
                       placeholder="Critical Quantity" class="form-control {{ $inputClassName }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            pcs.
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group text-right mb-0">
                <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
                    {{ $saveBtnName }}
                </button>
                <button type="reset" class="btn btn-secondary waves-effect waves-light">
                    CANCEL
                </button>
            </div>

        </div>
	</div>
</div>