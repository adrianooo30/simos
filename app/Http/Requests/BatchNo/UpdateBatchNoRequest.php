<?php

namespace App\Http\Requests\BatchNo;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBatchNoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'batch_no' => 'required',
            'unit_cost' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'exp_date' => 'required',
            'date_added' => 'required',
            'supplier_id' => 'required',
        ];
    }
}
