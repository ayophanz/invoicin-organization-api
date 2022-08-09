<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\UUID;

class Organization extends Model
{
    use HasFactory, SoftDeletes, UUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'type',
    ];

     /**
     * A organization has many addresses.
     *
     * @return HasMany the attached addresses
     */
    public function organizationAddresses() : HasMany
    {
        return $this->hasMany(OrganizationAddress::class);
    }
}
