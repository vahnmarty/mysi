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
        $this->form->fill([
            'payment' => [
                'application_fee' => config('settings.payment.application_fee')
            ]
        ]);

        $this->settings = Setting::pluck('value', 'config')->toArray();

    }
    protected function getFormSchema(): array 
    {
        return [
            Section::make('Admission')
                ->schema([
                    TextInput::make('payment.application_fee')
                    ->label("Application Fee")
                    ->numeric()
                    ->required()
                    ->lazy(),
                    TextInput::make('payment.application_fee')
                    ->label("Application Fee")
                    ->numeric()
                    ->required()
                    ->lazy(),
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

        $this->updateEnv('PAYMENT_APPLICATION_FEE', $data['payment']['application_fee'], 'app.timezone' );
        
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
