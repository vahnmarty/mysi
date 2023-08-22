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
        $year = 2028;
        $hspt_id = $year . str_pad($application->id, 5, '0', STR_PAD_LEFT);
        $application->hspt_id = $hspt_id;
        $application->save();
    }

    /**
     * Handle the Application "updated" event.
     */
    public function updated(Application $application): void
    {
        
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
