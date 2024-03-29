<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_uuid',
        'organization_address_type_id',
        'address',
        'city',
        'zipcode',
        'country_id'
    ];

    /**
     * An address belongs to a organization
     *
     * @return BelongsTo The attached organization.
     */
    public function organization() : BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * An address belongs to a address type.
     *
     * @return BelongsTo The attached address type.
     */
    public function addressType() : BelongsTo
    {
        return $this->belongsTo(OrganizationAddressType::class);
    }

    /**
     * An address belongs to a country.
     *
     * @return BelongsTo The attached address type.
     */
    public function country() : BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
