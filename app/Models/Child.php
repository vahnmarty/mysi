<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Child extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function application()
    {
        return $this->hasOne(Application::class);
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

    public function submittedApplication()
    {
        return $this->hasOne(Application::class)->submitted();
    }

    public function documents()
    {
        return $this->hasMany(AccommodationDocument::class);
    }

    public function getCurrentSchool()
    {
        return $this->current_school == 'Not Listed' ? $this->current_school_not_listed : $this->current_school;
    }

    public function getExpectedGraduationYear()
    {
        $current_grade = (int) $this->current_grade;

        if(is_numeric($current_grade)){
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
}
