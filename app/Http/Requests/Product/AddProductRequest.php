<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
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
            'product_img' => 'sometimes',
            'generic_name' => 'required',
            'brand_name' => 'required',
            'weight_volume' => 'required',
            'strength' => 'required',
            'product_unit' => 'required',
            'unit_price' => 'required|numeric|min:1',
            'critical_quantity' => 'required|numeric|min:1',
        ];
    }
}
