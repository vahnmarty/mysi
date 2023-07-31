<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use Livewire\Component as Livewire;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\Child;
use App\Models\Legacy;
use App\Models\School;
use App\Models\Activity;
use App\Enums\ParentType;
use App\Enums\RacialType;
use App\Rules\MaxWordCount;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use App\Forms\Components\WordTextArea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;

trait ActivityFormTrait{

    public function getActivityForm()
    {
        return [
            Placeholder::make('section_school_activity')
                ->label('')
                ->content(new HtmlString('
                <p>* This section is to be completed by the applicant only.</p>
                <p>List up to four current extracurricular activities that you are most passionate about.</p>
            ')),
            Repeater::make('activities')
                ->label('')
                ->validationAttribute('School Activities')
                ->createItemButtonLabel('Add Activity')
                ->defaultItems(1)
                ->minItems(1)
                ->maxItems(4)
                ->disableItemMovement()
                ->registerListeners([
                    'repeater::deleteItem' => [
                        function (Component $component, string $statePath, string $uuidToDelete): void {
                            $items = $component->getState();
                            $activities = Activity::where('application_id', $this->app->id)->get();

                            foreach($activities as $index => $activity){
                                $existing = collect($items)->where('id', $activity->id)->first();

                                if(!$existing){
                                    $activity->delete();
                                }
                            }
                        },
                    ],
                ])
                ->schema([
                    Hidden::make('id')
                        ->afterStateHydrated(function(Hidden $component, Closure $set, Closure $get, $state){
                            if(!$state){
                                $activity = Activity::create(['application_id' => $this->app->id]);
                                $set('id', $activity->id);
                            }
                        }),
                    TextInput::make('activity_name')
                        ->label('Activity Name')
                        ->lazy()
                        ->required()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveActivity($get('id'), 'activity_name', $state);
                        }),
                    Select::make('number_of_years')
                        ->label('Number of Years')
                        ->options($this->getYearsOptions())
                        ->preload()
                        ->lazy()
                        ->required()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveActivity($get('id'), 'number_of_years', $state);
                        }),
                    Select::make('hours_per_week')
                        ->label('Hours per Week')
                        ->options($this->getHoursPerWeekOptions())
                        ->preload()
                        ->lazy()
                        ->required()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveActivity($get('id'), 'hours_per_week', $state);
                        }),
                    WordTextArea::make('activity_information')
                        ->label("Explain your involvement in this activity.  For example, the team(s) you play on, position(s) you play, concert(s)/recital(s) you have performed in, and/or why you are involved in this activity.")
                        ->helperText("Please limit your answer to 75 words.")
                        ->required()
                        ->rows(5)
                        ->lazy()
                        ->wordLimit(75)
                        ->rules([
                            new MaxWordCount(75)
                        ])
                        ->afterStateUpdated(function(Livewire $livewire, WordTextArea $component, Closure $get, $state){
                            $livewire->validateOnly($component->getStatePath());
                            $this->autoSaveActivity($get('id'), 'activity_information', $state);
                        }),
                    ]),
            Grid::make(1)
                ->visible(fn() => count($this->data['activities'] ?? []))
                ->schema([
                    WordTextArea::make('most_passionate_activity')
                        ->label("From the activities listed above, select the activity you are most passionate about continuing at SI and describe how you would contribute to this activity.")
                        ->helperText("Please limit your answer to 75 words.")
                        ->lazy()
                        ->required()
                        ->rows(5)
                        ->wordLimit(75)
                        ->rules([
                            new MaxWordCount(75, 100)
                        ])
                        ->afterStateUpdated(function(Livewire $livewire, WordTextArea $component, Closure $get, $state){
                            $livewire->validateOnly($component->getStatePath());
                            $this->autoSave('most_passionate_activity', $state);
                        }),
                    WordTextArea::make('new_extracurricular_activities')
                        ->label("Select two new extracurricular activities that you would like to be involved in at SI.  Explain why these activities appeal to you.")
                        ->helperText("Please limit your answer to 75 words.")
                        ->lazy()
                        ->required()
                        ->rows(5)
                        ->wordLimit(75)
                        ->rules([
                            new MaxWordCount(75, 100)
                        ])
                        ->afterStateUpdated(function(Livewire $livewire, WordTextArea $component, Closure $get, $state){
                            $livewire->validateOnly($component->getStatePath());
                            $this->autoSave('new_extracurricular_activities', $state);
                        }),
                ])
            
        ];
    }

    public function getYearsOptions()
    {
        $arr = range(1, 9);
        $arrs = array_combine($arr, $arr);

        return $arrs + ['10+' => '10+'];
    }

    public function getHoursPerWeekOptions()
    {
        $arr = ['1 - 2', '3 - 5', '6 - 10', '11 - 15', '16+'];
        $arrs = array_combine($arr, $arr);
        return $arrs;
    }

    private function autoSaveActivity($id, $column, $value)
    {
        $model = Activity::find($id);
        $this->__autoSave($model, $column, $value);
    }

}