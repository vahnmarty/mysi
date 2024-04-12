<?php

namespace App\Filament\Resources\CurrentStudentFinancialAidResource\Pages;

use Filament\Pages\Actions;
use App\Services\NotificationService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Models\CurrentStudentFinancialAid;
use App\Filament\Resources\CurrentStudentFinancialAidResource;

class ListCurrentStudentFinancialAids extends ListRecords
{
    protected static string $resource = CurrentStudentFinancialAidResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Record'),
            Actions\Action::make('generate_letters')
                ->label('Generate Letters')
                ->requiresConfirmation()
                ->action('generateLetters')
        ];
    }

    public function generateLetters()
    {
        $count = 0;
        $students = CurrentStudentFinancialAid::get();

        foreach($students as $currentStudent)
        {
            $notification = new NotificationService;
            $letterContents = $notification->createCurrentStudentFinancialAid($currentStudent);

            $currentStudent->notification_sent_at = now();
            $currentStudent->fa_contents = $letterContents;
            $currentStudent->save();

            $count++;
        }

        Notification::make()
            ->title('Notification Sent')
            ->body('Successfully generated ' . $count . ' notifications.')
            ->success()
            ->send();
    }
}
