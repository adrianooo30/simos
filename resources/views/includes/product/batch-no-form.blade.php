<div class="card-box">
	<div class="row">

		<div class="col-md-6">

            <input type="hidden" name="product_id" class="{{ $inputClassName }}">
            <input type="hidden" name="batch_no_id" class="{{ $inputClassName }}">

            <div class="form-group">
                <label >Batch Number</label>
                <input type="text" name="batch_no" parsley-trigger="change" 
                       placeholder="Batch Number" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label >Quantity</label>
                <div class="input-group">
                    <input type="text" name="quantity" parsley-trigger="change" 
                       placeholder="Quantity" class="form-control {{ $inputClassName }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            pcs.
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label >Unit Cost</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            &#8369;
                        </div>
                    </div>
                    <input type="text" name="unit_cost" parsley-trigger="change" 
                           placeholder="Unit Cost" class="form-control {{ $inputClassName }}">
                </div>
            </div>

		</div>
		
		<div class="col-md-6">
			<div class="form-group">
                <label >Expiry Date</label>
                <input type="date" name="exp_date" parsley-trigger="change" 
                       placeholder="Expiry Date" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label >Date Added</label>
                <input type="date" name="date_added" parsley-trigger="change" 
                       placeholder="Date Added" class="form-control {{ $inputClassName }} --date-today">
            </div>

            <div class="form-group">
                <label >Supplier</label>

                <div class="input-group">
                    <select name="supplier_id" class="form-control {{ $inputClassName }}">
                        <option value="">--Select Supplier--</option>
                        @foreach( $suppliers as $supplier )
                            <option value="{{ $supplier['id'] }}">{{ $supplier['supplier_name'] }}</option>}
                        @endforeach
                    </select>
                    @if( $btnAddSupplier ?? true )
                        <div class="input-group-append">
                            <span class="input-group-addon">
                                <button type="button" class="btn btn-icon waves-effect btn-secondary" data-toggle="modal" data-target="#add-supplier-modal">
                                    <i class="far fa-user"></i>
                                </button>
                            </span>
                        </div>
                    @endif
                </div><!-- input-group -->
            </div>
		</div>

	</div> <!-- end of row -->

	<div class="form-group text-right mb-0">
	    <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
	        {{ $saveBtnName }}
	    </button>
	</div>
</div>