<?php

namespace App\Http\Livewire\Admission;

use App\Models\Child;
use Livewire\Component;
use App\Enums\GradeLevel;
use App\Models\Application;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class ViewApplications extends Component implements HasTable
{
    use InteractsWithTable;
    
    public function render()
    {
        return view('livewire.admission.view-applications');
    }

    public function mount()
    {
        $app = Application::where('account_id', accountId())->submitted()->get();

        if( count($app) == 1){
            return redirect()->route('application.show', $app[0]->uuid);
        }
        
    }

    public function getTableQuery()
    {
        return Child::where('account_id', accountId())->has('application');
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('student_name')
                ->label('Student Name')
                ->formatStateUsing(fn(Child $record) => $record->getFullName() ),
            TextColumn::make('mobile_phone')
                ->label('Mobile Phone')
                ->formatStateUsing(fn($state) => format_phone($state)),
            TextColumn::make('personal_email')
                ->label('Email'),
            TextColumn::make('current_school')
                ->label('Current School')
                ->wrap()
                ->formatStateUsing(fn(Child $record) => $record->getCurrentSchool()),
            TextColumn::make('current_grade')
                ->label('Current Grade'),
        ];
    }

    protected function isTablePaginationEnabled()
    {
        return false;
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('submitted')
                ->label('View Application')
                ->visible(function(Child $record){

                    if($record->application){
                        if($record->application->appStatus?->application_submitted){
                            return true;
                        }
                    }

                    return false;
                })
                ->url(fn(Child $record) => route('application.show', $record->application->uuid))
                ->extraAttributes(['class' => 'app-status'])
                ->color(''),
        ];
    }
}
