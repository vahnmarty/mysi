<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Enums\Suffix;
use App\Models\School;
use App\Enums\Salutation;
use App\Enums\AddressType;
use App\Enums\LivingSituationType;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;

trait ParentFormTrait{

    public function getParentForm()
    {
        return [
            Repeater::make('parents')
                ->createItemButtonLabel('Add Parent/Guardian')
                ->defaultItems(1)
                ->schema([
                    Select::make('salutation')
                        ->options(Salutation::asSameArray())
                        ->required(),
                    TextInput::make('first_name')
                        ->label('Legal First Name')
                        ->required(),
                    TextInput::make('middle_name')
                        ->label('Legal Middle Name')
                        ->required(),
                    TextInput::make('last_name')
                        ->label('Legal Last Name')
                        ->required(),
                    Select::make('suffix')
                        ->options(Suffix::asSelectArray())
                        ->required(),
                    TextInput::make('mobile_phone')
                        ->tel()
                        ->required(),
                    TextInput::make('personal_email')
                        ->label('Preferred Email')
                        ->email()
                        ->required(),
                    TextInput::make('employer'),
                    TextInput::make('job_title'),
                    TextInput::make('work_email')
                        ->email(),
                    TextInput::make('work_phone'),
                    TextInput::make('work_phone_ext'),
                    Textarea::make('schools_attended')
                        ->label('List all high schools, colleges, or graduate schools you have attended')
                        ->helperText('(Please limit answer to 75 words.)'),
                ])
            

        ];
    }

}