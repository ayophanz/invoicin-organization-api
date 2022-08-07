<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\OrganizationSetting;
use App\Models\OrganizationAddress;
use App\Transformers\OrganizationSettingTransformer;
use App\Transformers\OrganizationAddressTransformer;
use App\Traits\ApiResponser;
use Auth;

class OrganizationController extends Controller
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
        //
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

    /**
     * Show the settings resource
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        $transformer = new OrganizationSettingTransformer();
        $organizationSetting = OrganizationSetting::where('organization_id', $this->auth->organization_id)->get();
        return $this->successResponse(
            $transformer->transformCollection(
                $organizationSetting->transform(function ($item, $key) {
                    return $item;
                })->all(), Response::HTTP_OK)
        );
    }

    /**
     * Show the addresses resource
     * 
     * @param int $id
     * @return \Illuminate\http\Response
     */
    public function addresses()
    {
        $transformer = new OrganizationAddressTransformer();
        $organizationAddress = OrganizationAddress::where('organization_id', $this->auth->organization_id)->get();
        return $this->successResponse(
            $transformer->transformCollection(
                $organizationAddress->transform(function ($item, $key) {
                    return $item;
                })->all(), Response::HTTP_OK)
        );
    }
}
