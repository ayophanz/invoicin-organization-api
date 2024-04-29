<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Organization;
use App\Models\OrganizationSetting;
use App\Models\Address;
use App\Models\Country;
use App\Transformers\OrganizationTransformer;
use App\Transformers\CountryTransformer;
use App\Traits\ApiResponser;
use App\Http\Requests\Organization\StoreRequest;
use App\Http\Requests\Organization\UpdateProfileRequest;
use App\Events\RegisteredEvent;
use Auth;
use Image;
use Carbon\Carbon;
use Hashids\Hashids;

class OrganizationController extends Controller
{
    use ApiResponser;
    protected $transformer;

    public function __construct(OrganizationTransformer $transformer)
    {
        $this->transformer = $transformer;
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
     * Validate org registration request.
     *
     * @param  \Illuminate\Http\StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function orgValidate(StoreRequest $request)
    {
        return $this->successResponse(['success' => true], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $organization        = new Organization();
        $organization->name  = $request->organization_name;
        $organization->email = $request->organization_email;
        $organization->save();

        if (count($request->logo) > 0) {
            $logo = 'company.jpg';
            $path = storage_path() . '/app/public/files/company_' . $organization->uuid. '/logo/';
            \File::isDirectory($path) or \File::makeDirectory($path, 0777, true, true);
            Image::make($request->logo[0])->save($path . $logo);
        }

        RegisteredEvent::dispatch($organization);

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
        $organization = Organization::find(Auth::user()->organization_id);
        if ($organization)
            return $this->successResponse($this->transformer->transform($organization), Response::HTTP_OK);

        return $this->errorResponse(['error' => 'Not found'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Show the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $organization
     * @return \Illuminate\Http\Response
     */
    public function showProfile(Request $request)
    {
        $organization = Organization::find(Auth::user()->organization_id);
        if ($organization)
            return $this->successResponse($this->transformer->transform($organization), Response::HTTP_OK);

        return $this->errorResponse(['error' => 'Not found'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $organization
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $organization = Organization::find(Auth::user()->organization_id);
        if ($organization) {
            $organization->name = $request->name;
            $organization->email = $request->email;
            $organization->save();

            return $this->successResponse(['success' => true], Response::HTTP_OK);
        }

        return $this->errorResponse(['error' => 'Not found'], Response::HTTP_INTERNAL_SERVER_ERROR);
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

    public function verifyOrganization(Request $request)
    {
        $organization = Organization::where('uuid', $request->id)->first();
        if ($organization->email_verified_at != null) {
            return $this->successResponse(['status' => 'Your organization is already verified!'], Response::HTTP_OK);
        }

        $organization->email_verified_at = Carbon::now();
        $organization->save();

        return $this->successResponse(['status' => 'Your organization is successfully verified!'], Response::HTTP_OK);
    }
}
