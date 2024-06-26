<?php

namespace App\Http\Controllers;

use App\Http\Requests\Address\UpdateRequest;
use App\Http\Resources\Address\AddressCollection;
use App\Models\AddressType;
use App\Models\Organization;
use App\Traits\ApiResponser;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $organization = Organization::find($this->auth->organization_uuid);
        $addresses = $organization->addresses()->get();

        return new AddressCollection($addresses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $organization = Organization::find($this->auth->organization_uuid);
        $addressType = AddressType::where('name', $request->type)->first();
        $organization->addresses()->updateOrCreate(
            [
                'address_type_id' => $addressType->id,
                'organization_uuid' => $organization->uuid,
            ],
            [
                'country' => $request->country,
                'state_province' => $request->state_province,
                'city' => $request->city,
                'zipcode' => $request->zipcode,
                'address' => $request->address,
            ]
        );

        return $this->successResponse(['success' => true], Response::HTTP_OK);
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
