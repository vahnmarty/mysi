<?php

namespace App\Http\Livewire\Application\Forms;

use Str;
use Closure;
use App\Enums\Suffix;
use App\Models\School;
use App\Enums\Salutation;
use App\Enums\AddressType;
use App\Enums\LivingSituationType;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use App\Models\Parents as ParentModel;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput\Mask;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea as NewTextArea;

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
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'salutation', $state);
                        }),
                    TextInput::make('first_name')
                        ->label('Legal First Name')
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'first_name', $state);
                        }),
                    TextInput::make('middle_name')
                        ->label('Legal Middle Name')
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'middle_name', $state);
                        }),
                    TextInput::make('last_name')
                        ->label('Legal Last Name')
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'last_name', $state);
                        }),
                    Select::make('suffix')
                        ->options(Suffix::asSelectArray())
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'suffix', $state);
                        }),
                    TextInput::make('mobile_phone')
                        ->tel()
                        ->mask(fn (Mask $mask) => $mask->pattern('(000) 000-0000'))
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'mobile_phone', $state);
                        }),
                    TextInput::make('personal_email')
                        ->label('Preferred Email')
                        ->email()
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'personal_email', $state);
                        }),
                    TextInput::make('employer')
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'employer', $state);
                        }),
                    TextInput::make('job_title')
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'job_title', $state);
                        }),
                    TextInput::make('work_email')
                        ->email()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'work_email', $state);
                        }),
                    TextInput::make('work_phone')
                        ->mask(fn (Mask $mask) => $mask->pattern('(000) 000-0000'))
                        ->tel()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'work_phone', $state);
                        }),
                    TextInput::make('work_phone_ext')
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'work_phone_ext', $state);
                        }),
                    NewTextArea::make('schools_attended')
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'schools_attended', $state);
                        })
                        ->maxLength(500)
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

    private function autoSaveParent($id, $column, $value)
    {
        $model = ParentModel::find($id);
        $model->$column = $value;
        $model->save();
    }

}