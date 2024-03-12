<?php

namespace App\Filament\Pages;

use File;
use Artisan;
use Closure;
use App\Models\Setting;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;
use App\Models\NotificationSetting;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Actions\Action as PageAction;

class SystemVariables extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament.pages.settings';

    protected static ?string $navigationLabel = 'System Variables';


    public function mount()
    {
        $timeline = NotificationSetting::pluck('value', 'config')->toArray();

        $this->form->fill([
            'payment' => [
                'application_fee' => config('settings.payment.application_fee'),
                'tuition_fee' => config('settings.payment.tuition_fee')
            ],
            'academic_year' =>  config('settings.academic_year'),
            'number_of_applicants' => config('settings.number_of_applicants'),
            'class_year' => config('settings.class_year'),
            'timeline' => $timeline,
            'registration' => config('settings.registration')
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
                    // Grid::make(4)
                    //     ->schema([
                    //         TextInput::make('academic_year')
                    //             ->label('Academic Year (YYYY - YYYY)')
                    //             ->required()
                    //             ->placeholder('YYYY - YYYY')
                    //             ->required(),
                    //     ]),
                ]),
            Section::make('Notification Letter Variables')
                ->schema([
                    // Grid::make(4)
                    //     ->schema([
                    //         DatePicker::make('timeline.notification_date')
                    //             ->label("Notification Date"),
                    //     ]),
                    // Grid::make(4)
                    //     ->schema([
                    //         DateTimePicker::make('timeline.acceptance_deadline_date')
                    //             ->label("Acceptance Deadline Date"),
                    //     ]),
                    // Grid::make(4)
                    //     ->schema([
                    //         DateTimePicker::make('timeline.registration_start_date')
                    //             ->label("Registration Start Date"),
                    //         DateTimePicker::make('timeline.registration_end_date')
                    //             ->label("Registration End Date"),
                    //     ]),
                    Grid::make(4)
                        ->schema([
                            TextInput::make('payment.tuition_fee')
                            ->label("Tuition Fee")
                            ->numeric()
                            ->required()
                            ->lazy(),
                        ]),
                    // Grid::make(4)
                    //     ->schema([
                    //         TextInput::make('class_year')
                    //         ->label("Class Year")
                    //         ->required()
                    //         ->lazy(),
                    //     ]),
                    // Grid::make(4)
                    //     ->schema([
                    //         TextInput::make('number_of_applicants')
                    //         ->label("Number of Applicants")
                    //         ->required()
                    //         ->lazy(),
                    //     ]),
                ]),

        ];
    } 

    protected function getActions(): array
    {
        return [
            PageAction::make('clear_cache')
                ->label('Clear Cache')
                ->requiresConfirmation()
                ->action('clearCache')
                ->color('secondary'),
            PageAction::make('save_changes')
                ->requiresConfirmation()
                ->action('updateChanges'),

        ];
    }

    public function updateChanges()
    {
        $data = $this->form->getState();

        $this->updateEnv('PAYMENT_APPLICATION_FEE', $data['payment']['application_fee']);
        $this->updateEnv('PAYMENT_TUITION_FEE', $data['payment']['tuition_fee']);
        //$this->updateEnv('ACADEMIC_YEAR', $data['academic_year']);
        //$this->updateEnv('CLASS_YEAR', $data['class_year']);
        //$this->updateEnv('NUMBER_OF_APPLICANTS', $data['number_of_applicants']);
        
        
        // foreach($data['timeline'] as $config => $value){
        //     $setting = NotificationSetting::where('config', $config)->first();
        //     $setting->value = $value;
        //     $setting->save();
        // }
    }

    public function updateEnv($env, $value, $config = null)
    {
        $envFilePath = base_path('.env');
        $value = '"' . $value . '"';

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
