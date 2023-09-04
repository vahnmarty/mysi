<?php

namespace App\Http\Livewire\Application;

use App\Models\Parents;
use Livewire\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea;

class SupplementalRecommendationRequestForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $data = [];
    
    public function render()
    {
        return view('livewire.application.supplemental-recommendation-request-form');
    }

    public function mount()
    {
        $this->form->fill();
    }

    protected function getFormSchema()
    {
        return [
            Select::make('requester')
                ->label('Requester')
                ->options(Parents::where('account_id', accountId())->pluck('first_name', 'id'))
                ->required(),
            TextInput::make('requester_email')
                ->label('Requester Email')
                ->email()
                ->required(),
            TextInput::make('recommender_name')
                ->label('Name of Recommender')
                ->required(),
            TextInput::make('recommender_email')
                ->label('Recommender Email')
                ->email()
                ->required(),
            Textarea::make('message')
                ->maxLength(2000)
                ->required()

        ];
    }

    protected function getFormStatePath(): string 
    {
        return 'data';
    }
}
