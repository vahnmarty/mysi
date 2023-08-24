<?php

namespace App\Observers;

use App\Models\Child;

class ChildObserver
{
    /**
     * Handle the Child "created" event.
     */
    public function created(Child $child): void
    {
        $child->expected_graduation_year = $child->getExpectedGraduationYear();
        $child->expected_enrollment_year = $child->getExpectedEnrollmentYear();
        $child->save();
    }

    /**
     * Handle the Child "updated" event.
     */
    public function updated(Child $child): void
    {
        $child->expected_graduation_year = $child->getExpectedGraduationYear();
        $child->expected_enrollment_year = $child->getExpectedEnrollmentYear();
        $child->save();
    }

    /**
     * Handle the Child "deleted" event.
     */
    public function deleted(Child $child): void
    {
        //
    }

    /**
     * Handle the Child "restored" event.
     */
    public function restored(Child $child): void
    {
        //
    }

    /**
     * Handle the Child "force deleted" event.
     */
    public function forceDeleted(Child $child): void
    {
        //
    }
}
