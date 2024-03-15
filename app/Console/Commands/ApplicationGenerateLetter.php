<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApplicationStatus;
use App\Enums\NotificationStatusType;
use App\Jobs\ProcessNotificationMessage;

class ApplicationGenerateLetter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-letter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appStatuses = ApplicationStatus::with('application')
            ->where('application_status', '!=', NotificationStatusType::NoResponse)
            ->whereNotNull('application_status')
            ->whereNull('candidate_decision')
            ->get();

        foreach($appStatuses as $appStatus)
        {
            ProcessNotificationMessage::dispatch($appStatus);
        }
    }
}
