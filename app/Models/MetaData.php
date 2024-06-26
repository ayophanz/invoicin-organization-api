<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaData extends Model
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

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'sourceable_id');
    }
}
