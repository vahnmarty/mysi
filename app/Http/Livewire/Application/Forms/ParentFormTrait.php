<?php

namespace App\Http\Livewire\Application\Forms;

use Str;
use Closure;
use App\Enums\Suffix;
use App\Models\School;
use App\Models\Parents as ParentModel;
use App\Enums\Salutation;
use App\Enums\AddressType;
use App\Enums\LivingSituationType;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Component;
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
                ->schema([
                    Hidden::make('id')
                        ->afterStateHydrated(function(Hidden $component, Closure $set, Closure $get, $state){
                            if(!$state){
                                $parentModel = ParentModel::create(['account_id' => $this->app->account_id]);

                                $set('id', $parentModel->id);
                            }
                        }),
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
                ->registerListeners([
                    'repeater::deleteItem' => [
                        function (Component $component, string $statePath, string $uuidToDelete): void {
                            $items = $component->getState();
                            $parents = ParentModel::where('account_id', $this->app->account_id)->get();

                            foreach($parents as $parent){
                                $existing = collect($items)->where('id', $parent->id)->first();

                                if(!$existing){
                                    $parent->delete();
                                }
                            }
                        },
                    ],
                ])
        ];
    }

}