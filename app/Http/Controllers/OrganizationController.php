<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Organization;
use App\Models\OrganizationSetting;
use App\Models\OrganizationAddress;
use App\Transformers\OrganizationTransformer;
use App\Transformers\OrganizationSettingTransformer;
use App\Transformers\OrganizationAddressTransformer;
use App\Traits\ApiResponser;
use Auth;

class OrganizationController extends Controller
{
    use ApiResponser;

    protected $auth;
    protected $transformer;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(OrganizationTransformer $transformer)
    {
        $this->transformer = $transformer;
        $this->auth        = Auth::user();
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
        $organization        = new Organization();
        $organization->name  = $request->organization_name;
        $organization->email = $request->organization_email;
        $organization->type  = $request->organization_type;
        $organization->save();

        return $this->successResponse($organization, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $organization = Organization::find($this->auth->organization_id);
        return $this->successResponse($this->transformer->transform($organization), Response::HTTP_OK);
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
    public function update(Request $request, $id)
    {
        //
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
