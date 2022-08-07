<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\OrganizationAddress;
use App\Transformers\OrganizationAddressTransformer;
use App\Traits\ApiResponser;
use Auth;

class OrganizationAddressController extends Controller
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
    public function __construct(OrganizationAddressTransformer $transformer)
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
            'country_id'                   => 'required|numeric',
            'address'                      => 'required|string',
            'organization_address_type_id' => [
                'required',
                'numeric',
                Rule::unique('organization_addresses')
                    ->using(function ($q) { 
                        $q->where('organization_id', $this->auth->organization_id); 
                    })
            ],
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $address = [];
        try {
            /** Save here */
            $address  = OrganizationAddress::create([
                'organization_id'              => $this->auth->organization_id,
                'organizaiton_address_type_id' => $request->organization_address_type_id,
                'country_id'                   => $request->country_id,
                'address'                      => $request->address,
            ]);
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($this->transformer->transform($address), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
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
            'country_id'                   => 'required|numeric',
            'address'                      => 'required|string',
            'organization_address_type_id' => [
                'required',
                'numeric',
                Rule::unique('organization_addresses')
                    ->using(function ($q) { 
                        $q->where('organization_id', $this->auth->organization_id); 
                    })->ignore($request->organization_address_type_id, 'organization_address_type_id')
            ],
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $address = [];
        try {
            /** Update here */
            $address = tap(OrganizationAddress::where('organization_id', $this->auth->organization_id)->where('organization_address_type_id', $request->organization_address_type_id))
                ->update([
                    'address'    => $request->address,
                    'country_id' => $request->country_id,
                ])->first();
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($this->transformer->transform($address), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
