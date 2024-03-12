<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Survey extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function schools()
    {
        return $this->hasMany(SurveySchool::class);
    }

    public function submitted()
    {
        return !empty($this->submitted_at);
    }

    public function getStatus()
    {
        return $this->submitted() ? 'Completed' : 'Not completed';
    }
}
