<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
