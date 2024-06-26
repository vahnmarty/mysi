<?php

namespace App\Models;

use Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ReRegistration extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function completed()
    {
        return $this->completed_at;
    }

    public function declined()
    {
        return !$this->attending_si;
    }

    public static function isEnabled()
    {
        $re_registration_start_date = notification_setting('re_registration_start_date');
        $re_registration_end_date = notification_setting('re_registration_end_date');

        $app_start_date = $re_registration_start_date->value;
        $app_end_date = $re_registration_end_date->value;

        return now()->gte($app_start_date) && now()->lt($app_end_date) || empty($app_start_date)  || empty($app_end_date);
    }
}
