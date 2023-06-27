<?php

namespace App\Filament\Pages;

use Closure;
use Filament\Pages\Page;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament.pages.settings';

    public $env = [];

    public function mount()
    {
        $this->envForm->fill(config('settings'));

    }

    protected function getEnvFormStatePath()
    {
        return 'env';
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
                ])
            
        ];
    }

    protected function getForms(): array 
    {
        return [
            'envForm' => $this->makeForm()
                ->schema($this->getEnvFormSchema())
                ->statePath($this->getEnvFormStatePath())
        ];
    } 
}
