<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\GradeLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Child extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $appends = [
        'pronoun_subject',
        'pronoun_possessive',
        'pronoun_personal',
        'official_school'
    ];

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function application()
    {
        return $this->hasOne(Application::class);
    }

    public function registration()
    {
        return $this->hasOne(Registration::class);
    }

    public function submitted()
    {
        if($this->application)
        {
            if($this->application->appStatus?->application_submitted){
                return true;
            }
        }

        return false;
    }

    public function scopeStudent($query)
    {
        return $query->where('current_grade', GradeLevel::Grade8);
    }

    public function scopeStudentWithUser($query)
    {
        return $query->student()->where;
    }

    public function submittedApplication()
    {
        return $this->hasOne(Application::class)->submitted();
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function documents()
    {
        return $this->hasMany(AccommodationDocument::class);
    }

    public function recommendations()
    {
        return $this->hasMany(SupplementalRecommendation::class);
    }

    public function getCurrentSchool()
    {
        return $this->current_school == 'Not Listed' ? $this->current_school_not_listed : $this->current_school;
    }

    public function getOfficialSchoolAttribute()
    {
        return $this->getCurrentSchool();
    }

    public function getExpectedGraduationYear()
    {
        if(is_numeric($this->current_grade)){
            $current_grade = (int) $this->current_grade;
            return 12 - $current_grade + 1 + date('Y');
        }

        if($this->current_grade == GradeLevel::Kindergarten){
            return 2036;
        }

        if($this->current_grade == GradeLevel::PreKindergarten){
            return 2037;
        }

        return null;
    }

    public function getExpectedEnrollmentYear()
    {
        $year = $this->getExpectedGraduationYear();

        if($year >= 2028){
            return $year ? $year - 4 : null;
        }
        
        return null;
    }

    public function getPronounSubjectAttribute()
    {
        return $this->gender == Gender::Male ? 'he' : 'she';
    }
    
    public function getPronounPossessiveAttribute()
    {
        return $this->gender == Gender::Male ? 'his' : 'her';
    }

    public function getPronounPersonalAttribute()
    {
        return $this->gender == Gender::Male ? 'him' : 'her';
    }
}
