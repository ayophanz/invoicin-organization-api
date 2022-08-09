<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationSetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'sourceable_id', 
        'sourceable_type',
        'key', 
        'value'
    ];

    public function sourceable()
    {
        return $this->morphTo();
    }

    /**
     * An address belongs to a organization
     *
     * @return BelongsTo The attached organization.
     */
    public function organization() : BelongsTo
    {
        return $this->belongsTo(Organization::class, 'sourceable_id');
    }
}
