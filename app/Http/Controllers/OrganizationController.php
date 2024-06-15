<?php

namespace App\Http\Controllers;

use App\Events\RegisteredEvent;
use App\Http\Requests\Organization\StoreRequest;
use App\Http\Requests\Organization\UpdateProfileRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\AddressType;
use App\Models\Organization;
use App\Traits\ApiResponser;
use App\Traits\BgColorTrait;
use App\Traits\ImageTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrganizationController extends Controller
{
    use ApiResponser;
    use BgColorTrait;
    use ImageTrait;

    public function __construct()
    {
        //
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
     * Validate org registration request.
     *
     * @param  \Illuminate\Http\StoreRequest  $request
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
        \DB::beginTransaction();
        try {
            $organization = new Organization();
            $organization->name = $request->organization_name;
            $organization->email = $request->organization_email;
            $organization->save();

            $organization->metaData()->create([
                'key' => 'default_profile_logo_bg',
                'value' => $this->randomBgColor(),
            ]);

            $addressType = AddressType::where('name', 'Billing')->first();
            $organization->addresses()->create(
                [
                    'address_type_id' => $addressType->id,
                    'organization_uuid' => $organization->uuid,
                    'country' => $request->country,
                    'state_province' => $request->state_province,
                    'city' => $request->city,
                    'zipcode' => $request->zipcode,
                    'address' => $request->address,
                ]
            );
            RegisteredEvent::dispatch($organization);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();

            return $this->errorResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

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
        $organization = Organization::find(Auth::user()->organization_uuid);
        if ($organization) {
            return new OrganizationResource($organization);
        }

        return $this->errorResponse(['error' => 'Not found'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Show the specified resource in storage.
     *
     * @param  int  $organization
     * @return \Illuminate\Http\Response
     */
    public function showProfile(Request $request)
    {
        $organization = Organization::find(Auth::user()->organization_uuid);
        if ($organization) {
            return new OrganizationResource($organization);
        }

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
        \DB::beginTransaction();
        try {
            $organization = Organization::find(Auth::user()->organization_uuid);
            $organization->name = $request->name;
            $organization->email = $request->email;
            $organization->save();

            if (count($request->logo) > 0) {
                $this->storeImage($organization, $request->logo[0]);
            } else {
                if ($organization->image_path !== null &&
                    $organization->image_path !== '' &&
                    \File::exists(public_path().$organization->image_path)
                ) {
                    unlink(public_path().$organization->image_path);
                }
                $organization->image_path = '';
                $organization->save();
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();

            return $this->errorResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse(['success' => true], Response::HTTP_OK);
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
            return $this->successResponse(['status' => 'Your organization is already verified!', 'email_verified_at' => $organization->email_verified_at], Response::HTTP_OK);
        }

        $organization->email_verified_at = Carbon::now();
        $organization->save();

        return $this->successResponse(['status' => 'Your organization is successfully verified!', 'email_verified_at' => $organization->email_verified_at], Response::HTTP_OK);
    }
}
