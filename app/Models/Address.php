<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_uuid',
        'address_type_id',
        'address',
        'city',
        'state_province',
        'zipcode',
        'country'
    ];

    /**
     * An address belongs to a organization
     */
    public function organization() : BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * An address belongs to a address type.
     */
    public function addressType() : BelongsTo
    {
        return $this->belongsTo(AddressType::class);
    }
}
