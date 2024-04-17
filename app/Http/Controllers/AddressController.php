<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Organization;
use App\Models\Address;
use App\Transformers\AddressTransformer;
use App\Traits\ApiResponser;
use Auth;

class AddressController extends Controller
{
    use ApiResponser;

    protected $transformer;
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(AddressTransformer $transformer)
    {
        $this->transformer = $transformer;
        $this->auth = Auth::user();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** Validation here */
        $toValidate = [
            'country'         => 'required',
            'state_province'  => 'required',
            'city'            => 'required',
            'zipcode'         => 'required',
            'address'         => 'required|string',
            'address_type_id' => [
                'required',
                'numeric',
                Rule::unique('addresses')
                    ->using(function ($q) { 
                        $q->where('organization_uuid', $this->auth->organization_id);
                    })
            ],
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);

        try {
            /** Save here */
            $organization = Organization::find($this->auth->organization_id);
            $address      =  $organization->addresses()->create([
                'address_type_id' => $request->address_type_id,
                'country'         => $request->country,
                'state_province'  => $request->state_province,
                'city'            => $request->city,
                'zipcode'         => $request->zipcode,
                'address'         => $request->address,
            ]);

            return $this->successResponse($this->transformer->transform($address), Response::HTTP_CREATED);
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        try {
            $organization = Organization::find($this->auth->organization_id);
            $address      = $organization->addresses()->where('address_type_id', $request->address_type_id)->first();

            return $this->successResponse($this->transformer->transform($address), Response::HTTP_OK);
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
         /** Validation here */
         $toValidate = [
            'country'         => 'required',
            'state_province'  => 'required',
            'city'            => 'required',
            'zipcode'         => 'required',
            'address'         => 'required|string',
            'address_type_id' => [
                'required',
                'numeric',
                Rule::unique('addresses')
                    ->using(function ($q) { 
                        $q->where('organization_uuid', $this->auth->organization_id); 
                    })->ignore($request->address_type_id, 'address_type_id')
            ],
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);

        try {
            /** Update here */
            $organization = Organization::find($this->auth->organization_id);
            $address      = tap($organization->addresses()->where('address_type_id', $request->address_type_id))
                ->update([
                    'country'         => $request->country,
                    'state_province'  => $request->state_province,
                    'city'            => $request->city,
                    'zipcode'         => $request->zipcode,
                    'address'         => $request->address,
                ])->first();

            return $this->successResponse($this->transformer->transform($address), Response::HTTP_OK);
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $organization = Organization::find($this->auth->organization_id);
            $address      = $organization->addresses()->where('address_type_id', $request->address_type_id)->delete();

            return $this->successResponse(['Success' => $address ? true : false], Response::HTTP_OK);
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
