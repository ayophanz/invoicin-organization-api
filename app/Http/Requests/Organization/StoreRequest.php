<?php

namespace App\Http\Requests\Organization;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
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
        \Log::debug('test');
        return [
            'logo.*'   => 'base64size:1MB|base64mimes:png,jpg,jpeg',
            'type'     => 'required',
            'name'     => 'required',
            'orgEmail' => 'required|email|unique:users,email',
            'address'  => 'required',
            'city'     => 'required',
            'zipcode'  => 'required|numeric',
            'country'  => 'required|numeric',
        ];
    }
}