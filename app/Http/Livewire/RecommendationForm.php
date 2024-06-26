<?php

namespace App\Http\Livewire;

use App\Models\Parents;
use Livewire\Component;
use App\Rules\MaxWordCount;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use App\Forms\Components\WordTextArea;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Models\SupplementalRecommendation;
use Filament\Forms\Concerns\InteractsWithForms;
use Wiebenieuwenhuis\FilamentCharCounter\TextInput as TextInputCounter;

class RecommendationForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $data = [];

    public $done = false;
    public $uuid;
    
    public function render()
    {
        return view('livewire.recommendation-form')->layout('layouts.guest');
    }

    public function mount($uuid)
    {
        $this->uuid = $uuid;
        $rec = SupplementalRecommendation::where('uuid', $uuid)->firstOrFail();

        if($rec->date_received){
            return $this->done = true;
        }

        $this->form->fill([
            'recommender_name' => $rec->recommender_first_name . ' ' . $rec->recommender_last_name,
            'recommender_email' => $rec->recommender_email,
        ]);
    }

    protected function getFormStatePath(): string 
    {
        return 'data';
    }

    protected function getFormSchema()
    {
        return [
            Grid::make(2)
                ->schema([
                    TextInput::make('recommender_name')
                        ->label('Name of Recommender')
                        ->required()
                        ->disabled()
                        ->columnSpan(2),
                    TextInput::make('recommender_email')
                        ->label('Recommender Email')
                        ->email()
                        ->disabled()
                        ->required()
                        ->columnSpan(2),
                    TextInputCounter::make('relationship_to_student')
                        ->label('Relationship to Student')
                        ->required()
                        ->columnSpan(2)
                        ->maxLength(40),
                    Select::make('years_known_student')
                        ->label('Years Known Student')
                        ->required()
                        ->options(function(){
                            $array = range(1, 14);
                            $years =  array_combine($array, $array);
                            $years['15+'] = '15+';

                            return $years;
                        })
                        ->columnSpan(2),
                    WordTextArea::make('recommendation')
                        ->maxLength(2250)
                        ->required()
                        ->columnSpan(2)
                        ->helperText('Please limit your answer to 250 words.')
                        ->rows(13)
                        ->lazy()
                        ->required()
                        ->wordLimit(250)
                        ->rules([
                            new MaxWordCount(250,300)
                        ])
                ])
        ];
    }

    public function save()
    {
        $data = $this->form->getState();

        $rec = SupplementalRecommendation::where('uuid', $this->uuid)->first();

        $rec->relationship_to_student = $data['relationship_to_student'];
        $rec->years_known_student = $data['years_known_student'];
        $rec->recommendation = $data['recommendation'];
        $rec->date_received = date('Y-m-d');
        $rec->status = true;
        $rec->save();

        $this->done = true;
    }
}
