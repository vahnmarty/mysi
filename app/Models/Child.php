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

    public function getCurrentSchool()
    {
        return $this->current_school == 'Not Listed' ? $this->current_school_not_listed : $this->current_school;
    }

    public function getExpectedGraduationYear()
    {
        // Calculated field; do not use if in college or beyond 
        //  -- Difference between 12th grade and current grade plus 1 plus current year; 
         
        //  Ex. 8th grader in 2025 ((12 - 8) + 1 + 2025 = 4 + 1 + 2025 = 2030)

        $current_grade = (int) $this->current_grade;

        if(is_numeric($current_grade)){
            $extra_year = date('Y') + 1 + 1; // +1 because in the sample docs it's 2025. 

            $expected_graduation_year = 12 - $current_grade + 1 + $extra_year;

            return $expected_graduation_year;
        }

        return null;
    }

    public function getExpectedEnrollmentYear()
    {
        // only used when the child does not attend high school/college -- expected_graduation_year - 5; Ex. 2030 - 5 = 2025

        $year = $this->getExpectedGraduationYear();
        return $year ? $year - 5 : null;
    }
}
