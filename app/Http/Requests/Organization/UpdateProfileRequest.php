<?php

namespace App\Http\Requests\Organization;

use App\Http\Requests\BaseRequest;

class UpdateProfileRequest extends BaseRequest
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
            'logo.*' => 'base64mimes:png,jpg,jpeg,webp',
            'name'   => 'required',
            'email'  => 'required|email|unique:organizations,email,'.$this->organization->id,
        ];
    }
}
