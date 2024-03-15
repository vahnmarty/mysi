<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\ApplicationStatus;
use App\Enums\CandidateDecisionType;
use App\Enums\NotificationStatusType;
use App\Services\NotificationService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessNotificationMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ApplicationStatus $appStatus;

    /**
     * Create a new job instance.
     */
    public function __construct(ApplicationStatus $appStatus)
    {
        $this->appStatus = $appStatus;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {   
        $app = $this->appStatus->application;
        
        $app->notificationMessages()->delete();
        
        (new NotificationService)->createMessage($app);

        $this->appStatus->update([
            'candidate_decision_status' => CandidateDecisionType::NotificationSent
        ]);
    }
}
