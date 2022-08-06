<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'organization_address_type_id',
        'address',
        'country_id'
    ];

    /**
     * An address belongs to a address type.
     *
     * @return BelongsTo The attached address type.
     */
    public function organizationAddressType() : BelongsTo
    {
        return $this->belongsTo(CustomerAddressType::class);
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
