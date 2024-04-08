<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;


class ReRegistration extends Model
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

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function student()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function healthcare()
    {
        return $this->hasOne(Healthcare::class);
    }

    public function emergency_contact()
    {
        return $this->hasOne(EmergencyContact::class);
    }

    public function accommodation()
    {
        return $this->hasOne(Accommodation::class);
    }
}
