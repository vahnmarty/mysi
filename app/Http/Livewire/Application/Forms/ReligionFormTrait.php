<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\School;
use App\Enums\RacialType;
use App\Enums\ReligionType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput\Mask;

trait ReligionFormTrait{

    public function getReligionForm()
    {
        return [
            
            Select::make('student.religion')
                ->options(ReligionType::asSelectArray())
                ->label("Applicant(s)'s Religion")
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('religion', $state);
                }),
            TextInput::make('student.religion_other')
                ->label('If "Other," add it here')
                ->lazy()
                ->required()
                ->placeholder('Enter Religion')
                ->hidden(fn (Closure $get) => $get('student.religion') !== ReligionType::Other)
                ->afterStateUpdated(function(Closure $get, $state){
                    $this->autoSaveStudent('religion_other', $state);
                }),
            TextInput::make('student.religious_community')
                ->label('Church/Faith Community')
                ->lazy()
                ->afterStateUpdated(function(Closure $get, $state){
                    $this->autoSaveStudent('religious_community', $state);
                }),
            TextInput::make('student.religious_community_location')
                ->label('Church/Faith Community Location')
                ->lazy()
                ->afterStateUpdated(function(Closure $get, $state){
                    $this->autoSaveStudent('religious_community_location', $state);
                }),
            TextInput::make('student.baptism_year')
                ->label('Baptism Year')
                ->numeric()
                ->integer()
                ->minLength(4)
                ->maxLength(4)
                ->lazy()
                ->afterStateUpdated(function(Closure $get, $state){
                    $this->autoSaveStudent('baptism_year', $state);
                }),
            TextInput::make('student.confirmation_year')
                ->label('Confirmation Year')
                ->numeric()
                ->integer()
                ->minLength(4)
                ->maxLength(4)
                ->lazy()
                ->afterStateUpdated(function(Closure $get, $state){
                    $this->autoSaveStudent('confirmation_year', $state);
                }),
            
        ];
    }

    private function autoSaveStudentReligion($column, $value)
    {
        $model = $this->app->student;
        $model->$column = $value;
        $model->save();
    }
}