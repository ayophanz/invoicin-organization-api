<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\Address\AddressCollection;
use App\Models\Organization;
use App\Models\Address;
use App\Traits\ApiResponser;
use Auth;

class AddressController extends Controller
{
    use ApiResponser;

    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct()
    {
        $this->auth = Auth::user();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organization = Organization::find($this->auth->organization_id);
        $addresses    = $organization->addresses()->get();

        return new AddressCollection($addresses);
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
         //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }
}
