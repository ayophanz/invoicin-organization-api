<?php

namespace App\Http\Requests\Address;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
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
            'address' => 'required',
            'city' => 'required',
            'zipcode' => 'required|numeric',
            'state_province' => 'required',
            'country' => 'required',
        ];
    }
}
