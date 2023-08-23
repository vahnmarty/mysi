<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationDocument extends Model
{
    use HasFactory;

    protected $guarded = [] ;

    protected $casts = [
        'file' => 'array'
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
