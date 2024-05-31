<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
    ];

     /**
     * A organization has many addresses.
     */
    public function addresses() : HasMany
    {
        return $this->hasMany(Address::class);
    }

     /**
     * An customer has many organization metadata.
     */
    public function metaData() : MorphMany
    {
        return $this->morphMany(MetaData::class, 'sourceable');
    }
}
