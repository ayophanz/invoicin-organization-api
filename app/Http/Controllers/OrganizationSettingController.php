<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponser;
use App\Transformers\OrganizationSettingTransformer;
use App\Models\Organization;
use Auth;

class OrganizationSettingController extends Controller
{
    use ApiResponser;

    protected $auth;
    protected $transformer;

    public function __construct(OrganizationSettingTransformer $transformer)
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
         /** Validation here */
         $toValidate = [
            'value' => 'required',
            'key'   => [
                'required',
                Rule::unique('organization_settings')
                    ->using(function ($q) { 
                        $q->where('sourceable_id', $this->auth->organization_id)->where('sourceable_type', 'App\Models\Organization'); 
                    })
            ],
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);

        try {
            /** Save here */
            $organization = Organization::find($this->auth->organization_id);
            $setting      = $organization->settings()->create([
                'key'   => $request->key,
                'value' => $request->value,
            ]);

            return $this->successResponse($this->transformer->transform($setting), Response::HTTP_OK);
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
            $setting      = $organization->settings()->where('key', $request->key)->first();

            return $this->successResponse($this->transformer->transform($setting), Response::HTTP_OK);
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
            'key'   => 'required',
            'value' => 'required',
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);

        try {
            /** Update here */
            $organization = Organization::find( $this->auth->organization_id);
            $setting      = tap($organization->settings()->where('key', $request->key))->update([
                'value' => $request->value
            ])->first();

            return $this->successResponse($this->transformer->transform($setting), Response::HTTP_OK);
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
            $setting      = $organization->settings()->where('key', $request->key)->delete();

            return $this->successResponse(['Success' => $setting ? true : false], Response::HTTP_OK);
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
