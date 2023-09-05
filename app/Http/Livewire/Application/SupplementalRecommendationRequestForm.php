<?php

namespace App\Http\Livewire\Application;

use Closure;
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
                ->options(Parents::where('account_id', accountId())->get()->pluck('full_name', 'id'))
                ->required()
                ->reactive()
                ->afterStateUpdated(function(Closure $set, $state){
                    $parent = Parents::findOrFail($state);

                    $set('requester_email', $parent->personal_email);
                }),
            TextInput::make('requester_email')
                ->label('Requester Email')
                ->email()
                ->lazy()
                ->required(),
            TextInput::make('recommender_first_name')
                ->label('First Name of Recommender')
                ->required(),
            TextInput::make('recommender_last_name')
                ->label('Last Name of Recommender')
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
