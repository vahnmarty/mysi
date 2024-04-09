<?php

namespace App\Http\Livewire\Registration\ReregistrationForms;

use Str;
use Closure;
use App\Enums\Suffix;
use App\Models\School;
use App\Enums\ParentType;
use App\Enums\Salutation;
use App\Enums\AddressType;
use App\Enums\ParentSuffix;
use App\Rules\MaxWordCount;
use App\Enums\EmploymentStatus;
use App\Enums\MaritalStatusType;
use App\Enums\LivingSituationType;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use App\Forms\Components\WordTextArea;
use App\Models\Parents as ParentModel;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
                ->content(new HtmlString('* This section is to be completed by a parent/guardian.')),
            Repeater::make('parents')
                ->label('')
                ->createItemButtonLabel('Add Parent/Guardian')
                ->createItemButtonLabel(fn(Closure $get) => count($get('parents')) ? 'Add Another Parent/Guardian' : 'Add Parent/Guardian')
                ->disableItemMovement()
                ->minItems(1)
                ->maxItems(4)
                ->registerListeners([
                    'repeater::createItem' => [
                        function (Component $component, string $statePath): void {
                            if($statePath == 'data.parents'){
                                Notification::make()
                                    ->title('New Parent Form Added')
                                    ->body('Make sure to REFRESH this page after adding a new parent.')
                                    ->warning()
                                    ->send();
                            }
                            
                        }
                    ],
                    'repeater::deleteItem' => [
                        function (Component $component, string $statePath, string $uuidToDelete): void {
                            if($statePath == 'data.parents')
                            {
                                $items = $component->getState();
                                $parents = ParentModel::where('account_id', accountId())->get();
                                
                                if(count($parents) > 1){
                                    foreach($parents as $parent){
                                        $existing = collect($items)->where('id', $parent->id)->first();
        
                                        if(!$existing){
                                            $parent->delete();
                                        }
                                    }
                                }else{
                                    Notification::make()
                                        ->title('Unable to delete this parent')
                                        ->body('The application must have at least 1 parent.')
                                        ->danger()
                                        ->send();
                                }
                            }
                            
                            
                        },
                    ],
                ])
                ->schema([
                    Hidden::make('id')
                        ->afterStateHydrated(function(Hidden $component, Closure $set, Closure $get, $state){
                            if(!$state){
                                $parentModel = ParentModel::create(['account_id' => accountId()]);
                                $set('id', $parentModel->id);
                            }
                        }),
                    Select::make('relationship_type')
                        ->label('Relationship')
                        ->options(ParentType::asSameArray())
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'relationship_type', $state);
                        }),
                    Select::make('salutation')
                        ->options(Salutation::asSameArray())
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'salutation', $state);
                        }),
                    TextInput::make('first_name')
                        ->label('First Name')
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'first_name', $state);
                        }),
                    TextInput::make('middle_name')
                        ->label('Middle Name')
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'middle_name', $state);
                        }),
                    TextInput::make('last_name')
                        ->label('Last Name')
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
                        ->rules(['doesnt_start_with:1'])
                        ->validationAttribute('Phone Number')
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
                        ->afterStateUpdated(function(Livewire $livewire, TextInput $component, Closure $get, $state){
                            $livewire->validateOnly($component->getStatePath());
                            $this->autoSaveParent($get('id'),'personal_email', $state);
                        }),
                    Select::make('employment_status')
                        ->label('What is your employment status?')
                        ->reactive()
                        ->options(EmploymentStatus::asSameArray())
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'employment_status', $state);
                        })
                        ->required(),
                    TextInput::make('employer')
                        ->label(fn(Closure $get) => $get('employment_status') === EmploymentStatus::Retired ? 'Last Employer' : 'Employer')
                        ->lazy()
                        ->visible(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed, EmploymentStatus::Retired]) )
                        ->required(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed, EmploymentStatus::Retired]) )
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'employer', $state);
                        }),
                    TextInput::make('job_title')
                        ->label(fn(Closure $get) => $get('employment_status') === EmploymentStatus::Retired ? 'Last Job Title' : 'Job Title')
                        ->lazy()
                        ->visible(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed, EmploymentStatus::Retired]) )
                        ->required(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed, EmploymentStatus::Retired]) )
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'job_title', $state);
                        }),
                    TextInput::make('work_email')
                        ->label('Work Email')
                        ->email()
                        ->rules(['email:rfc,dns'])
                        ->lazy()
                        ->visible(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed]) )
                        ->required(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed]) )
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'work_email', $state);
                        }),
                    TextInput::make('work_phone')
                        ->label('Work Phone')
                        ->mask(fn (Mask $mask) => $mask->pattern('(000) 000-0000'))
                        ->tel()
                        ->lazy()
                        ->visible(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed]) )
                        ->required(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed]) )
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
                        ->visible(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed]) )
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'work_phone_ext', $state);
                        }),
                    Select::make('si_alumni_flag')
                        ->label('Graduated from SI?')
                        ->options([
                            1 => 'Yes',
                            0 => 'No'
                        ])
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'si_alumni_flag', $state);
                        }),
                    TextInput::make('graduation_year')
                        ->label('Graduation Year')
                        ->integer()
                        ->minLength(4)
                        ->maxLength(4)
                        ->maxValue(date('Y'))
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0000'))
                        ->lazy()
                        ->visible(fn(Closure $get) => $get('si_alumni_flag'))
                        ->afterStateUpdated(function(Livewire $livewire, TextInput $component, Closure $get, $state){
                            $livewire->validateOnly($component->getStatePath());
                            $this->autoSaveParent($get('id'),'graduation_year', $state);
                        }),
                    Select::make('is_primary_contact')
                        ->label("Is this parent/guardian the primary contact?  (Only 1 parent/guardian can be the primary contact.)")
                        ->options([
                            1 => 'Yes',
                            0 => 'No'
                        ])
                        ->required()
                        ->reactive()
                        ->disabled(fn(Closure $get) => ParentModel::where('account_id', accountId())->count() == 1)
                        ->afterStateHydrated(function(Closure $get, Closure $set, $state){
                            $parentsCount = ParentModel::where('account_id', accountId())->count();

                            if($parentsCount == 1){
                                $set('is_primary_contact', 1);

                                $parent = ParentModel::find($get('id'));
                                $parent->is_primary_contact = 1;
                                $parent->save();
                            }
                        })
                        ->afterStateUpdated(function(Closure $get, Closure $set, $state){

                            if($state == 1)
                            {   
                                $parentsRepeater = $get('../../parents');

                                foreach($parentsRepeater as $repeaterItemUuid => $parentItem){
                                    if($get('id') != $parentItem['id'])
                                    {
                                        # Backend
                                        $parentModel = ParentModel::find($parentItem['id']);
                                        $parentModel->is_primary_contact = false;
                                        $parentModel->save();
                                        
                                        # Frontend
                                        $set('../../parents.'. $repeaterItemUuid.'.is_primary_contact', 0);
                                    }
                                    
                                }
                            }
                            
                            $this->autoSaveParent($get('id'),'is_primary_contact', $state);
                        }),
                    Select::make('has_legal_custody')
                        ->label("Does this parent/guardian have legal custody of the student?")
                        ->options([
                            1 => 'Yes',
                            0 => 'No'
                        ])
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'has_legal_custody', $state);
                        }),
                    Select::make('is_pickup_allowed')
                        ->label("Is it okay for this parent/guardian to pick up the student at SI?")
                        ->options([
                            1 => 'Yes',
                            0 => 'No'
                        ])
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'is_pickup_allowed', $state);
                        }),
                    Select::make('marital_status')
                        ->label('What is your marital status?')
                        ->options(MaritalStatusType::asSameArray())
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'),'marital_status', $state);
                        }),
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