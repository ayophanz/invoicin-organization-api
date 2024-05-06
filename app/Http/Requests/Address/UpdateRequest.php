<?php

namespace App\Http\Requests\Address;

use App\Http\Requests\BaseRequest;
use Auth;

class UpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::check()) return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address'         => 'required',
            'city'            => 'required',
            'zipcode'         => 'required|numeric',
            'state_province'  => 'required',
            'country'         => 'required',
        ];
    }
}
