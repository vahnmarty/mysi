<?php

namespace App\Http\Livewire\Application\Forms;

use Str;
use Closure;
use App\Enums\Suffix;
use App\Models\School;
use App\Enums\Salutation;
use App\Enums\AddressType;
use App\Enums\ParentSuffix;
use App\Rules\MaxWordCount;
use App\Enums\EmploymentStatus;
use App\Enums\LivingSituationType;
use Illuminate\Support\HtmlString;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use App\Forms\Components\WordTextArea;
use App\Models\Parents as ParentModel;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput\Mask;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea as NewTextArea;

trait ParentFormTrait{

    public function getParentForm()
    {
        return [
            Placeholder::make('parent_form_description')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by a parent/guardian only.')),
            Repeater::make('parents')
                ->label('')
                ->createItemButtonLabel('Add Parent/Guardian')
                ->disableItemMovement()
                ->maxItems(4)
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
                        ->label('Suffix')
                        ->options(ParentSuffix::asSameArray())
                        ->preload()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'suffix', $state);
                        }),
                    TextInput::make('mobile_phone')
                        ->label('Mobile Phone')
                        ->tel()
                        ->mask(fn (Mask $mask) => $mask->pattern('(000) 000-0000'))
                        ->required()
                        ->lazy()
                        ->afterStateHydrated(function(Closure $set, $state){
                            if(!$state){
                                $set('mobile_phone', '');
                            }
                        })
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'mobile_phone', $state);
                        }),
                    TextInput::make('personal_email')
                        ->label('Preferred Email')
                        ->email()
                        ->rules(['email:rfc,dns'])
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'personal_email', $state);
                        }),
                    Select::make('employment_status')
                        ->label('What is your employment status?')
                        ->required()
                        ->reactive()
                        ->options(EmploymentStatus::asSameArray())
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'employment_status', $state);
                        }),
                    TextInput::make('employer')
                        ->label(fn(Closure $get) => $get('employment_status') === EmploymentStatus::Retired ? 'Last Employer' : 'Employer')
                        ->lazy()
                        ->visible(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed, EmploymentStatus::Retired]) )
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'employer', $state);
                        }),
                    TextInput::make('job_title')
                        ->label(fn(Closure $get) => $get('employment_status') === EmploymentStatus::Retired ? 'Last Job Title' : 'Job Title')
                        ->lazy()
                        ->visible(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed, EmploymentStatus::Retired]) )
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'job_title', $state);
                        }),
                    TextInput::make('work_email')
                        ->label('Work Email')
                        ->email()
                        ->rules(['email:rfc,dns'])
                        ->lazy()
                        ->visible(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed, EmploymentStatus::Retired]) )
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'work_email', $state);
                        }),
                    TextInput::make('work_phone')
                        ->label('Work Phone')
                        ->mask(fn (Mask $mask) => $mask->pattern('(000) 000-0000'))
                        ->tel()
                        ->lazy()
                        ->visible(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed, EmploymentStatus::Retired]) )
                        ->afterStateHydrated(function(Closure $set, $state){
                            if(!$state){
                                $set('work_phone', '');
                            }
                        })
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'work_phone', $state);
                        }),
                    TextInput::make('work_phone_ext')
                        ->label('Work Phone Extension')
                        ->lazy()
                        ->visible(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed, EmploymentStatus::Retired]) )
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'work_phone_ext', $state);
                        }),
                    WordTextArea::make('schools_attended')
                        ->label('List all high schools, colleges, or graduate schools you have attended')
                        ->lazy()
                        ->afterStateUpdated(function(Livewire $livewire, WordTextArea $component, Closure $get, $state){
                            $livewire->validateOnly($component->getStatePath());
                            $this->autoSaveParent($get('id'),'schools_attended', $state);
                        })
                        ->wordLimit(75)
                        ->rules([
                            new MaxWordCount(75, 100)
                        ])
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