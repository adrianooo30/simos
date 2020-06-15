<div class="mx-4">
    <div class="form-group">
        <label for="" class="font-12 mb-2">Type of Payment</label>
        <div class="input-group">
            <select name="type_of_payment" class="for-payment form-control" data-order-transaction-id="{{ $id }}" disabled required>
                <option disabled selected value="">-- Select Type Of Payment --</option>
                <option value="full">Full Payment</option>
                <option value="partial">Partial Payment</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="font-12 mb-2">Amount To Pay</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    &#8369;
                </div>
            </div>
            <input type="number" name="amount" placeholder="Amount to Pay" class="for-payment --dont-exceed-max form-control" max="{{ $bill_amount }}" data-order-transaction-id="{{ $id }}" disabled required>
        </div>
    </div>
</div>