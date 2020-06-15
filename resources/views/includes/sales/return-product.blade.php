<div class="card-box">	
	{{-- psr qouta cards --}}
	<div class="row">

		@foreach($sales_transaction['order_medicine'] as $orderMedicine)
            <div class="col-xl-12  mx-auto --product-return">
                <div class="card card-shadow">

    <div class="card-header">
        <div class="row">
            <div class="col-xl-6 d-flex">
                <img src="{{ $orderMedicine['product']['product_img'] }}" alt="" class="img-thumbnail image-50 mx-2">
                <div class="mx-2">
                    <h5 class="text-primary">{{ $orderMedicine['product']['product_name'] }}</h5>
                    <h6 class="text-muted">{{ $orderMedicine['product']['brand_name'] }}</h6>
                    <h6 class="text-muted">{!! $sales_transaction->pesoFormat($orderMedicine['unit_price']) !!}</h6>
                </div>
            </div>

            <div class="col-xl-6 d-flex">
                <div class="mx-2">
                    <h5 class="text-primary">{!! $sales_transaction->pesoFormat($orderMedicine['total_cost']) !!}</h5>
                    <sup class="text-muted">Total Cost</sup>

                    <div class="alert alert-danger">
                        <strong>{{ $sales_transaction->quantityFormat($orderMedicine['quantity_wo_free']) }} + {{ $sales_transaction->quantityFormat($orderMedicine['free']) }}</strong>
                    </div>
                    <sup class="text-muted">Total Quantity</sup>
                </div>
            </div>               
        </div>
    </div>
    

    <div class="card-body  --parent-order-medicine">

            {{-- ORDER BATCH NUMBERS --}}
            <div class="row">
                
                <div class="col-xl-12">

                    <h4 class="text-darker my-3">Select specific batch numbers</h4>

                    @foreach($orderMedicine->orderBatchNo as $orderBatchNo)
                        <div class="alert alert-primary --parent-order-batch-no">
                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox d-inline mx-1">
                                    <input type="checkbox"
                                            {{-- name="" --}}
                                            class="custom-control-input  --order-batch-no-checkbox"
                                            id="--checkbox-{{ $orderBatchNo['id'] }}" 
                                            data-order-medicine-id="{{ $orderMedicine['id'] }}"
                                            data-order-batch-no-id="{{ $orderBatchNo['id'] }}"
                                            value="">
                                    <label class="custom-control-label" for="--checkbox-{{ $orderBatchNo['id'] }}"></label>
                                </div>
                                <label for="--checkbox-{{ $orderBatchNo['id'] }}" class="font-12 c-pointer text-primary">
                                    {{ $orderBatchNo['batchNo']['batch_no'] }} - {{ $sales_transaction->quantityFormat($orderBatchNo['quantity']) }}
                                </label>
                            </div>

                            <div class="row --order-batch-no-forms" style="display: none">
                                <div class="col-xl-6 form-group">
                                    <label for="" class="text-primary font-12">Returned Quantity</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control text-center --dont-exceed-max for-return --returned-qty-for-batch-no" data-order-medicine-id="{{ $orderMedicine['id'] }}" data-order-batch-no-id="{{ $orderBatchNo['id'] }}" min="1" max="{{ $orderBatchNo['quantity'] }}" placeholder="--quantity pcs--">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                pcs.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 form-group">
                                    <label for="" class="text-primary font-12">Reason</label>
                                    <div class="input-group">
                                        <select name="status" class="form-control for-return  --returned-reason-for-batch-no" data-order-medicine-id="{{ $orderMedicine['id'] }}" data-order-batch-no-id="{{ $orderBatchNo['id'] }}">
                                            <option value="" selected disabled>--Select Reason--</option>
                                            <option>Damage</option>
                                            <option>Expired</option>    
                                            <option>Slow Moving</option>    
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                
            </div>

            {{-- order medicine summary --}}
            <div class="row  --order-medicine-summary --order-medicine-summary-{{ $orderMedicine['id'] }}" style="display: none;">
                <div class="col-xl-6">
                    <div class="form-group">
                        <label for="" class="text-primary font-12">Total Returned Quantity</label>
                        <div class="input-group">
                            <input type="number" name="return_quantity" class="form-control text-center for-return
                            --dont-exceed-max  --order-medicine-returned-qty --returned-qty-for-order-medicine" placeholder="--returned quantity--" min="0" disabled readonly 
                            data-order-medicine-id="{{ $orderMedicine['id'] }}">
                        </div>
                    </div>
                    @if($orderMedicine['free'] > 0)
                        <div class="form-group">
                            <label for="" class="text-primary font-12">Free</label>
                            <div class="input-group">
                                <input type="number" name="return_quantity_free" class="form-control text-center for-return --dont-exceed-max  --returned-qty-free-for-order-medicine" placeholder="--free quantity--" min="0" max="{{ $orderMedicine['free'] }}"  disabled
                                data-order-medicine-id="{{ $orderMedicine['id'] }}">
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-xl-6">                                
                    <div class="form-group">
                        <label for="" class="text-primary font-12">Status</label>
                        <div class="input-group">
                            <select name="status" class="form-control --returned-status-for-order-medicine for-return"
                                    data-order-medicine-id="{{ $orderMedicine['id'] }}">
                                <option value="" selected disabled>--Select Status--</option>
                                <option>Replace</option>
                                <option>Dont Replace</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

    </div>

                </div>
            </div>
            <hr>
        @endforeach

	</div>
	{{-- end of row --}}


    <button type="submit" class="btn btn-primary waves-effect wave-light float-right">
        SAVE RETURN
    </button>

</div>