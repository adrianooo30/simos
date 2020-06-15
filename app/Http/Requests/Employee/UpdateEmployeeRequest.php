<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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

            'fname' => 'required',
            'mname' => 'required',
            'lname' => 'required',

            // 'position_id' => 'required',

            'contact_no' => 'required',
            'email' => 'required|email:rfc',
            'address' => 'required',
            
            'username' => 'required',
            // 'password' => 'required',
        ];
    }
}
