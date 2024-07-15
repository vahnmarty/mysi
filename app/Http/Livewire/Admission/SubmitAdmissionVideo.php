<?php

namespace App\Http\Livewire\Admission;

use Mail;
use App\Models\Child;
use Livewire\Component;
use App\Models\HsptScore;
use App\Models\AdmissionVideo;
use Filament\Tables\Actions\Action;
use App\Mail\AdmissionVideoSubmitted;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class SubmitAdmissionVideo extends Component implements HasForms, HasTable
{
    use InteractsWithForms;

    use InteractsWithTable;
    
    public function render()
    {
        return view('livewire.admission.submit-admission-video');
    }

    public function mount()
    {
        foreach(Child::where('account_id', accountId())->get() as $child){
            if(!$child->admission_video){
                AdmissionVideo::firstOrCreate([
                    'account_id' => accountId(),
                    'child_id' => $child->id
                ]);
            }
        }
    }

    public function getTableQuery()
    {
        return AdmissionVideo::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('child.full_name')
                ->label('Student Name'),
            TextColumn::make('video_url')
                ->label('Video')
                ->url(fn(AdmissionVideo $record) => $record->video_url)
                ->openUrlInNewTab()
                ->wrap(),
            TextColumn::make('sent_at')
                ->label('Date Sent')
                ->dateTime('m/d/Y h:i a'),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('add_link')
                ->label('Add Link')
                ->visible(fn(AdmissionVideo $record) => !$record->sent_at)
                ->form([
                    TextInput::make('video_url')
                        ->label('Enter a valid url')
                        ->url()
                ])
                ->action(function(AdmissionVideo $record, array $data){
                    $record->video_url = $data['video_url'];
                    $record->save();

                    Mail::to('mysi_admin@siprep.org')->send(new AdmissionVideoSubmitted($record));

                    $record->sent_at = now();
                    $record->save();
                }),
            Action::make('sent_status')
                ->label('Sent')
                ->visible(fn(AdmissionVideo $record) => $record->sent_at)
                ->disabled()
        ];
    }

    public function isTablePaginationEnabled(): bool 
    {
        return false;
    }

    public function getTableEmptyStateIcon(): ?string 
    {
        return 'heroicon-o-collection';
    }
 
    public function getTableEmptyStateHeading(): ?string
    {
        return 'No Available Admission Video';
    }

    public function getTableEmptyStateDescription(): ?string
    {
        return 'No records available';
    }

    protected function getTableActionsColumnLabel(): ?string
    {
        return 'Action';
    }
}
