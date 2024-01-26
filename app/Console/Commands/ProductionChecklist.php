<?php

namespace App\Console\Commands;

use App\Models\School;
use Illuminate\Console\Command;
use App\Models\NotificationLetter;
use App\Models\NotificationSetting;
use App\Enums\NotificationStatusType;

class ProductionChecklist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:checklist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This app display all the settings.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->newLine(3);  
        
        # Configs
        $this->checkConfigs();
        $this->newLine();

        # Settings
        $this->checkSettings();
        $this->newLine();

        # Schools
        $this->line('C. Schools: ' . School::count());
        $this->newLine();

        # Notifications
        $this->checkNotificationSettings();
        $this->newLine();
        // todo here
        $this->checkNotificationLetters();


        $this->newLine(3);
    }

    public function checkNotificationSettings()
    {
        $this->line('D. Notification Settings');
        $this->newLine();

        $settings = NotificationSetting::get();

        foreach($settings as $setting)
        {
            if(!empty($setting->value)){
                $this->info($this->getSuccessIcon() . " {$setting->title}: {$setting->value}" );
            }else{
                $this->error($this->getFailedIcon() . " {$setting->title}: ");
            }
        }
    }

    public function checkNotificationLetters()
    {
        $this->line('E. Notification Letters');
        $this->newLine();

        $types = NotificationStatusType::asSelectArray();

        foreach($types as $status)
        {
            $notification = NotificationLetter::where('reference', $status)->first();

            if($notification){
                $this->info($this->getSuccessIcon() . " {$status}" );
            }else{
                $this->error($this->getFailedIcon() . " {$status}");
            }
        }

        $financial_aid = NotificationLetter::where('title', 'Financial Aid')->first();

        if($financial_aid){
            $this->info($this->getSuccessIcon() . " Financial Aid" );
        }else{
            $this->error($this->getFailedIcon() . " Financial Aid");
        }

    }

    public function checkSettings()
    {
        $this->line('B. Settings');
        $this->newLine();

        $array = ['placement_test_date'];

        foreach($array as $arr)
        {
            $this->validateSetting($arr);
        }
    }

    public function checkConfigs()
    {
        $this->line('A. Config/Environment');
        $this->newLine();

        $array = [
                'settings.si.admissions.email', 'settings.payment.application_fee', 
                'services.authorize.login_id', 'services.authorize.transaction_key',
                'services.salesforce.key', 'services.salesforce.secret'
            ];

        foreach($array as $path)
        {
            $this->validateConfig($path);
        }
    }

    public function validateConfig($path)
    {
        if(config($path)){
            $this->info($this->getSuccessIcon() . " {$path}: " . config($path));
        }else{
            $this->error($this->getFailedIcon() . " {$path}: null" );
        }
    }

    public function validateSetting($config)
    {
        if(settings($config)){
            $this->info($this->getSuccessIcon() . " {$config}: " . settings($config));
        }else{
            $this->error($this->getFailedIcon() . " {$config}: " . settings($config));
        }
    }

    

    public function getSuccessIcon()
    {
        return "\u{2705}";
    }

    public function getFailedIcon()
    {
        return "\u{274C}";
    }

    
}
