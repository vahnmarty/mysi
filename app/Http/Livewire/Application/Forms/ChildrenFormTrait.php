<?php

namespace App\Http\Livewire\Application\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

trait ChildrenFormTrait{

    const ChildModel = 'child';

    public function getChildrenForm()
    {
        return [
            TextInput::make(self::ChildModel .'.first_name')
                ->label('Legal First Name')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('first_name', $state, self::ChildModel);
                }),
            TextInput::make(self::ChildModel .'.last_name')
                ->label('Legal Last Name')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('last_name', $state, self::ChildModel);
                }),
            TextInput::make(self::ChildModel .'.middle_name')
                ->label('Legal Middle Name')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('middle_name', $state, self::ChildModel);
                }),
            TextInput::make(self::ChildModel .'.suffix')
                ->label('Suffix')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('suffix', $state, self::ChildModel);
                }),
            TextInput::make(self::ChildModel .'.preferred_first_name')
                ->label('Preferred First Name')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('preferred_first_name', $state, self::ChildModel);
                }),
            DatePicker::make(self::ChildModel .'.birthdate')
                ->label('Date of Birth')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('birthdate', $state, self::ChildModel);
                }),
        ];
    }
}