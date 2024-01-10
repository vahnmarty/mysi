<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Str;

class Registration extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [] ;
    

    public static function boot()
    {
        parent::boot();
        
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function student()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function healthcare()
    {
        return $this->hasOne(Healthcare::class);
    }

    public function emergencyContact()
    {
        return $this->hasOne(EmergencyContact::class);
    }

    public function accommodation()
    {
        return $this->hasOne(Accommodation::class);
    }

    public function magisProgram()
    {
        return $this->hasOne(MagisProgram::class);
    }

    public function coursePlacement()
    {
        return $this->hasOne(coursePlacement::class);
    }
}
