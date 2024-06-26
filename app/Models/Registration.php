<?php

namespace App\Models;

use Str;
use App\Enums\GradeLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function registrationFee()
    {
        return $this->through('application')->has('payment');
        return $this->hasOne(Payment::class)->where('payment_type', 'RegFee')->latest();
    }

    public function completed()
    {
        return $this->application->appStatus->registration_completed;
    }

    public function started() : bool
    {
        return $this->started_at ? true : false;
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

    public function magis_program()
    {
        return $this->hasOne(MagisProgram::class);
    }

    public function course_placement()
    {
        return $this->hasOne(CoursePlacement::class);
    }

    public function scopeForReregistration($query)
    {
        return $query->whereHas('application', function($appQuery){
            $appQuery->whereHas('student', function($sQuery){
                $sQuery->whereIn('current_grade', [GradeLevel::Freshman, GradeLevel::Sophomore]);
            });
        });
    }
}
