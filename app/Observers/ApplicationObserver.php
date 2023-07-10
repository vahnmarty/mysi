<?php

namespace App\Observers;

use App\Models\Application;

class ApplicationObserver
{
    /**
     * Handle the Application "created" event.
     */
    public function created(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "updated" event.
     */
    public function updated(Application $application): void
    {
        /* expected_graduation_year
         Calculated field; do not use if in college or beyond 
         -- Difference between 12th grade and current grade plus 1 plus current year; 
         
         Ex. 8th grader in 2025 ((12 - 8) + 1 + 2025 = 4 + 1 + 2025 = 2030)

        */
        // if($application->current_grade){
        //     $var = 
        //     $application->
        // }
    }

    /**
     * Handle the Application "deleted" event.
     */
    public function deleted(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "restored" event.
     */
    public function restored(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "force deleted" event.
     */
    public function forceDeleted(Application $application): void
    {
        //
    }
}
