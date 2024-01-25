<?php

namespace App\Filament\Pages;

use File;
use Closure;
use Artisan;
use App\Models\Setting;
use Filament\Pages\Page;
use Filament\Pages\Actions\Action;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;

class SystemVariables extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament.pages.settings';

    protected static ?string $navigationLabel = 'System Variables';


    public function mount()
    {
        $academic_year = config('settings.academic_year');

        $academic_year_arr = explode('-', $academic_year);

        $this->form->fill([
            'payment' => [
                'application_fee' => config('settings.payment.application_fee'),
                'tuition_fee' => config('settings.payment.tuition_fee')
            ],
            'academic_year_1' => $academic_year_arr[0],
            'academic_year_2' => $academic_year_arr[1],
        ]);

        $this->settings = Setting::pluck('value', 'config')->toArray();

    }
    protected function getFormSchema(): array 
    {
        $years = range(2000, date('Y') + 10);
        return [
            Section::make('Admission')
                ->schema([
                    Grid::make(4)
                        ->schema([
                            TextInput::make('payment.application_fee')
                            ->label("Application Fee")
                            
                            ->numeric()
                            ->required()
                            ->lazy(),
                        ]),
                    Grid::make(4)
                        ->schema([
                            Select::make('academic_year_1')
                                ->label('Academic Year (From)')
                                ->required()
                                ->placeholder('YYYY')
                                ->required()
                                ->options(array_combine($years, $years)),
                            Select::make('academic_year_2')
                                ->label('Academic Year (To)')
                                ->required()
                                ->placeholder('YYYY')
                                ->required()
                                ->options(array_combine($years, $years))
                        ]),
                    Grid::make(4)
                        ->schema([
                            TextInput::make('payment.tuition_fee')
                            ->label("Tuition Fee")
                            ->numeric()
                            ->required()
                            ->lazy(),
                        ]),
                ])
        ];
    } 

    protected function getActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Cache')
                ->requiresConfirmation()
                ->action('clearCache')
                ->color('secondary'),
            Action::make('save_changes')
                ->requiresConfirmation()
                ->action('updateChanges'),

        ];
    }

    public function updateChanges()
    {
        $data = $this->form->getState();

        $this->updateEnv('PAYMENT_APPLICATION_FEE', $data['payment']['application_fee']);
        $this->updateEnv('PAYMENT_TUITION_FEE', $data['payment']['tuition_fee']);
        $this->updateEnv('ACADEMIC_YEAR', $data['academic_year_1'] . '-' . $data['academic_year_2']);
        
    }

    public function updateEnv($env, $value, $config = null)
    {
        $envFilePath = base_path('.env');

        if (File::exists($envFilePath)) {
            $envContent = File::get($envFilePath);

            // replace the value of the config variable
            $envContent = preg_replace('/^' . $env . '=.*/m', $env . '=' . $value, $envContent);

            // write the updated content to the .env file
            try {
                File::put($envFilePath, $envContent);

                $this->clearCache();

                Notification::make()
                    ->title('Settings updated successfully!')
                    ->body("The {$env} environment variable has been updated. Please note that changes may not be reflected immediately. If you do not see the new value, please wait a few minutes and refresh the page.")
                    ->success()
                    ->send();

                //$this->$env = config($config);
                
                # Hard Refresh
                //return redirect(request()->header('Referer'));

            } catch (\Throwable $th) {
                throw $th;
            }
        
        }
    }

    public function clearCache()
    {
        Artisan::call('config:cache');

        Notification::make()
            ->title('Cache Clear')
            ->success()
            ->send();
    }


}
