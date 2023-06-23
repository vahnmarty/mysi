<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        
        $this->checkSettings();

        $this->newLine(3);
    }

    public function checkSettings()
    {
        $this->line('Settings');
        $this->newLine();

        $array = ['placement_test_date'];

        foreach($array as $arr)
        {
            $this->validateSetting($arr);
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
