<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Organization;
use App\Models\OrganizationSetting;
use App\Models\OrganizationAddress;
use App\Models\Country;
use App\Transformers\OrganizationTransformer;
use App\Transformers\CountryTransformer;
use App\Traits\ApiResponser;
use App\Http\Requests\Organization\StoreRequest;
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
        return $this->successResponse(['success' => true], Response::HTTP_OK);;
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
        $organization->name  = $request->name;
        $organization->email = $request->orgEmail;
        $organization->type  = $request->type;
        $organization->save();

        if (count($request->logo) > 0) {
            $logo = 'company.jpg';
            $path = storage_path() . '/app/files/company_' . $organization->uuid. '/logo/';
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

    /**
     * All countries.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function countries()
    {
        $countries = Country::get();
        $this->transformer = new CountryTransformer();
        ;
        return $this->successResponse(
            $this->transformer->transformCollection(
                    $countries->transform(function($item, $key) {
                    return $item;
                })->all()
            ), 
        Response::HTTP_OK);
    }

    public function verifyOrganization(Request $request)
    {
        $hashids   = new Hashids();
        $decodedID = $hashids->decode($request->token);

        $organization = Organization::where('uuid', $decodedID)->first();
        if ($organization->email_verified_at != null) {
            return $this->successResponse(['Status' => 'Already verified'], Response::HTTP_OK);
        }

        $organization->email_verified_at = Carbon::now();
        $organization->save();

        return $this->successResponse(['Status' => 'Verified'], Response::HTTP_OK);
    }
}
