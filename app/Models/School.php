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

    public function scopeMiddleSchool($query)
    {
        return $query->where('education_level' , 'LIKE' , '%Middle%' );
    }

    public function scopeHighSchool($query)
    {
        return $query->where('education_level' , 'LIKE' , '%High%' );
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('name', 'LIKE', '%' . $keyword . '%');
    }

    public function getSchoolLevelAttribute()
    {
        return $this->name . ' (' . $this->education_level . ')';
    }
}
