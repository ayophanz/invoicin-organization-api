<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationSetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'organization_id',
        'sourceable_id', 
        'sourceable_type',
        'key', 
        'value'
    ];

    public function sourceable()
    {
        return $this->morphTo();
    }
}
