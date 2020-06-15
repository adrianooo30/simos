<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
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
            'profile_img' => 'sometimes',
            'account_name' => 'required',
            'type' => 'required',
            'address' => 'required',
            'contact_no' => 'required',
            'contact_person' => 'required',
        ];
    }
}
