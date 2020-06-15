<div class="row p-2">

    @foreach($receivables['order_medicine'] as $orderMedicine)

        @php
            $orderMedicine_payee = [];

            foreach($payment_order_medicines as $payment_order_medicine){
                if( $payment_order_medicine['order_medicine_id'] == $orderMedicine['id'] ) {
                    $orderMedicine_payee['quantity'] = $payment_order_medicine['quantity'];
                    $orderMedicine_payee['total_payment_amount'] = $payment_order_medicine['total_payment_amount'];
                }
            }
        @endphp
        {{-- order medicine --}}
        <div class="col-xl-12">
            <div class="card card-shadow" id="--order-transaction-card-{{ $receivables['id'] }}">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xl-5 d-flex">
                            <img src="{{ $orderMedicine['product']['product_img'] }}" alt="" class="img-thumbnail image-50 mx-2">
                            <div class="mx-2">
                                <h5 class="text-primary">{{ $orderMedicine['product']['product_name'] }}</h5>
                                <h6 class="text-muted">{{ $orderMedicine['product']['brand_name'] }}</h6>
                                <h6 class="text-muted">{!! $orderMedicine->pesoFormat($orderMedicine['unit_price']) !!}</h6>
                            </div>
                        </div>

                        <div class="col-xl-5 col-xs-8">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-l-12 col-xl-12">
                                        <h5 class="text-primary">
                                            {!!
                                                is_null($orderMedicine['payable_cost']) ?
                                                        $orderMedicine->pesoFormat($orderMedicine['total_cost']) :
                                                        $orderMedicine->pesoFormat($orderMedicine['payable_cost'])
                                                    !!}</h5>
                                        <h6 class="text-muted">Payables Cost</h6>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-l-12 col-xl-12">
                                        <strong>{{
                                                    is_null($orderMedicine['payable_quantity']) ?
                                                        $orderMedicine->quantityFormat($orderMedicine['quantity_wo_free']) :
                                                        $orderMedicine->quantityFormat($orderMedicine['payable_quantity'])
                                                }}</strong>
                                        <h6 class="text-muted">Payables Quantity</h6>
                                    </div>
                                </div>
                        </div>
                        <div class="col-xl-2 col-xs-4 --order-medicine-actions">
                            <input type="checkbox" id="--medicines-checkbox-o-{{ $orderMedicine['id'] }}" class="d-none --medicines-checkbox-o" data-order-medicine-id="{{ $orderMedicine['id'] }}" data-order-transaction-id="{{ $receivables['id'] }}" {{ count($orderMedicine_payee) > 0 ? 'checked="true"' : '' }}>
                            <label for="--medicines-checkbox-o-{{ $orderMedicine['id'] }}" class="btn btn-outline-success {{ count($orderMedicine_payee) ? 'text-white bg-success' : 'bg-white text-success' }}  my-1 --order-medicine-label  waves-effect wave-light">
                                <i class="ti-check-box  font-weight-bold font-14"></i>
                            </label>
                        </div>               
                    </div>
                </div>

                <div class="card-body" {!! count($orderMedicine_payee) == 0 ? 'style="display: none;"' : '' !!}>
                
                    <div class="row  --order-medicine-form">

                        <div class="col-xl-6 col-l-6 col-md-6 col-sm-6 form-group">
                            <label for="" class="text-primary font-12">Quantity</label>
                            <div class="input-group">
                                <input type="number"
                                    class="form-control text-center --dont-exceed-max --order-medicine-quantity"
                                    min="1"
                                    max="{{ is_null($orderMedicine['payable_quantity']) ? 
                                                $orderMedicine['quantity_wo_free'] :
                                                $orderMedicine['payable_quantity']
                                            }}"
                                    placeholder="--quantity pcs--"
                                    value="{{ $orderMedicine_payee['quantity'] ?? '' }}"
                                    data-order-transaction-id="{{ $receivables['id'] }}"
                                    data-order-medicine-id="{{ $orderMedicine['id'] }}"
                                    data-unit-price="{{ $orderMedicine['unit_price'] }}" >

                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        pcs.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-l-6 col-md-6 col-sm-6 form-group">
                            <label for="" class="text-primary font-12">Total Paid Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        &#8369;
                                    </div>
                                </div>
                                <input type="number" class="form-control text-center --order-medicine-total-paid-amount" placeholder="--total paid amount--" readonly disabled value="{{ $orderMedicine_payee['total_payment_amount'] ?? '' }}">
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    @endforeach

</div>