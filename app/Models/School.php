<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\GeneralModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;
    use GeneralModelTrait;

    protected $guarded = [];
}
