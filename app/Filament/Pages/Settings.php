<?php

namespace App\Filament\Pages;

use Closure;
use App\Models\Setting;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static ?string $navigationGroup = 'Configuration';
    
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament.pages.settings';

    public $env = [], $settings = [];

    public function mount()
    {
        $this->envForm->fill(config('settings'));

        $this->settings = Setting::pluck('value', 'config')->toArray();

    }

    protected function getEnvFormStatePath()
    {
        return 'env';
    }

    protected function getSettingsFormStatePath()
    {
        return 'settings';
    }

    protected function getEnvFormSchema(): array
    {
        return [
            Section::make('Environment Setting')
                ->schema([
                    Toggle::make('edit')
                        ->reactive()
                        ->label('Edit?'),
                    TextInput::make('si.admissions.email')
                        ->label("Admission's Email Address")
                        ->required()
                        ->lazy()
                        ->disabled(fn(Closure $get) => !$get('edit')),
                    TextInput::make('payment.application_fee')
                        ->label("Application Fee")
                        ->numeric()
                        ->required()
                        ->lazy()
                        ->disabled(fn(Closure $get) => !$get('edit')),
                ]),
            
        ];
    }

    protected function getSettingsFormSchema(): array
    {
        return [
            Section::make('General Settings')
                ->schema($this->getSettingsSchema()),
            
        ];
    }

    public function getSettingsSchema()
    {
        $settings = Setting::get();

        $array = [];

        foreach($settings as $setting)
        {
            $array[] = Grid::make(3)
                    ->extraAttributes(['class' => 'bg-gray-100 py-2 px-2'])
                    ->schema([
                        Placeholder::make("{$setting->config}_label")
                            ->disableLabel()
                            ->content($this->customInlineLabel($setting->config, $setting->description)),
                        DatePicker::make($setting->config)
                            ->disableLabel()
                            ->lazy(),
                        Placeholder::make("{$setting->config}_update")
                            ->disableLabel()
                            ->content($this->updateButton($setting->config)),
                    ]);
        }

        return $array;
    }

    protected function getForms(): array 
    {
        return [
            'envForm' => $this->makeForm()
                ->schema($this->getEnvFormSchema())
                ->statePath($this->getEnvFormStatePath()),
            'settingsForm' => $this->makeForm()
                ->schema($this->getSettingsFormSchema())
                ->statePath($this->getSettingsFormStatePath()),
        ];
    } 

    public function customInlineLabel($config, $name)
    {
        return new HtmlString("<h6 class='mb-1 text-sm font-bold'>{$config}</h6><p class='text-xs'>{$name}</p>");
    }

    public function updateButton($config)
    {
        return new HtmlString("
        <div class='flex justify-center'>
            <button type='button 
                x-data x-on:click='confirm(`Confirm Changes?`)' wire:click='save'
                class='px-2 py-1 text-primary-red hover:underline'>
                Update
            </button>
        </div>
        ");
    }


}
