<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use Closure;
use App\Models\Application;
use Filament\Pages\Actions;
use App\Models\ApplicationStatus;
use Illuminate\Support\HtmlString;
use App\Enums\CandidateDecisionType;
use App\Enums\NotificationStatusType;
use App\Services\NotificationService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\ApplicationResource;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\CreateAction::make(),
            Actions\Action::make('generate_letters')
                ->label('Generate Letters')
                ->requiresConfirmation()
                ->form([
                    Placeholder::make('total_accepted')
                        ->label('')
                        ->content(new HtmlString('<p>Accepted: <strong class="text-primary-red">' . $this->getTotalAccepted() .'</strong></p>')),
                    // Placeholder::make('total_accepted_honors')
                    //     ->label('')
                    //     ->content(new HtmlString('<p>Accepted with Honors: <strong class="text-primary-red">' . $this->getTotalAcceptedWithHonors() .'</strong></p>')),
                    Placeholder::make('waitlisted')
                        ->label('')
                        ->content(new HtmlString('<p>Waitlisted: <strong class="text-primary-red">' . $this->countUsingStatus(NotificationStatusType::WaitListed) .'</strong></p>')),
                    Placeholder::make('not_accepted')
                        ->label('')
                        ->content(new HtmlString('<p>Not Accepted: <strong class="text-primary-red">' . $this->countUsingStatus(NotificationStatusType::NotAccepted) .'</strong></p>')),
                ])
                ->action('generateLetters')
        ];
    }

    public function generateLetters()
    {
        $count = 0;
        $statuses = ApplicationStatus::with('application')
            ->where('application_status', '!=', NotificationStatusType::NoResponse)
            ->whereNotNull('application_status')
            ->whereNull('candidate_decision')
            ->get();

            

        foreach($statuses as $appStatus)
        {
            $app = $appStatus->application;

            $appStatus->update([
                'candidate_decision_status' => CandidateDecisionType::NotificationSent
            ]);

            $app->notificationMessages()->delete();
            $service = new NotificationService;
            $letterType = $service->createMessage($app);
            

            $count++;
        }

        Notification::make()
            ->title('Notification Sent')
            ->body('Successfully generated ' . $count . ' notifications.')
            ->success()
            ->send();
    }

    protected function getTableActionsColumnLabel(): ?string
    {
        return 'Actions';
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }


    protected function getTotalAccepted()
    {
        return ApplicationStatus::where('application_status', 'Accepted')->count();
                // ->where(function($query){
                //     $query
                //         ->orWhere('honors_math', 0)->orWhere('honors_math')
                //         ->orWhere('honors_english', 0)->orWhere('honors_english')
                //         ->orWhere('honors_bio', 0)->orWhere('honors_bio');
                // })
                // ->count();
    }

    protected function getTotalAcceptedWithHonors()
    {
        return ApplicationStatus::where('application_status', 'Accepted')
            ->where('honors_math',1)
            ->orWhere('honors_english', 1)
            ->orWhere('honors_bio', 1)
            ->count();
    }

    protected function countUsingStatus($type)
    {
        return ApplicationStatus::where('application_status', $type)
                ->count();
    }
    
}
