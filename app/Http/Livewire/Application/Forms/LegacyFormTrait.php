<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\Child;
use App\Models\Legacy;
use App\Models\School;
use App\Enums\ParentType;
use App\Enums\RacialType;
use App\Enums\LegacyParentType;
use Illuminate\Support\HtmlString;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;

trait LegacyFormTrait{

    public function getLegacyForm()
    {
        return [
            Placeholder::make('legacy_form_description')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by a parent/guardian.')),
            Repeater::make('legacy')
                ->label('List up to 5 relatives who have attended SI.  Do not include siblings.')
                ->createItemButtonLabel(fn(Closure $get) => count($get('legacy')) ? 'Add Another Legacy Relative' : 'Add Legacy Relative')
                ->defaultItems(1)
                ->disableItemMovement()
                ->maxItems(5)
                ->registerListeners([
                    'repeater::deleteItem' => [
                        function (Component $component, string $statePath, string $uuidToDelete): void {
                            if($statePath == 'data.legacy')
                            {
                                $items = $component->getState();
                                $legacies = Legacy::where('account_id', $this->app->account_id)->get();
        
                                foreach($legacies as $index => $legacy){
                                    $existing = collect($items)->where('id', $legacy->id)->first();
        
                                    if(!$existing){
                                        $legacy->delete();
                                    }
                                }
                            }
                            
                        },
                    ],
                ])
                ->schema([
                    Hidden::make('id')
                        ->afterStateHydrated(function(Hidden $component, Closure $set, Closure $get, $state){
                            if(!$state){
                                $legacy = Legacy::create([
                                        'account_id' => $this->app->account_id,
                                        'application_id' => $this->app->id,
                                    ]);
                                $set('id', $legacy->id);
                            }
                        }),
                    TextInput::make('first_name')
                        ->label('First Name')
                        ->lazy()
                        ->required()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveLegacy($get('id'), 'first_name', $state);
                        }),
                    TextInput::make('last_name')
                        ->label('Last Name')
                        ->lazy()
                        ->required()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveLegacy($get('id'), 'last_name', $state);
                        }),
                    Select::make('relationship_type')
                        ->label('Relationship to Applicant')
                        ->options(LegacyParentType::asSameArray())
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveLegacy($get('id'), 'relationship_type', $state);
                        }),
                    TextInput::make('graduation_year')
                        ->label('Graduation Year')
                        ->lazy()
                        ->numeric()
                        ->minLength(4)
                        ->maxLength(4)
                        ->maxValue(2027)
                        ->required()
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0000'))
                        ->afterStateUpdated(function(Livewire $livewire, Closure $get, TextInput $component, $state){
                            $livewire->validateOnly($component->getStatePath());
                            $this->autoSaveLegacy($get('id'), 'graduation_year', $state);
                        }),
            ])
            
            
        ];
    }

    private function autoSaveLegacy($id, $column, $value)
    {
        $model = Legacy::find($id);
        $model->$column = $value;
        $model->save();
    }

}