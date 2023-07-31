<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationArchive extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'application' => 'array',
        'student' => 'array',
        'addresses' => 'array',
        'parents' => 'array',
        'parents_matrix' => 'array',
        'siblings' => 'array',
        'siblings_matrix' => 'array',
        'legacy' => 'array',
        'activities' => 'array',
    ];
}
