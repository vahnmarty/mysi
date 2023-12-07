<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TransferData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:transfer-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer Data from Prod to Sandbox';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $models = ['Account', 'Child', 'Application', 'Parents', 'Address', 'Legacy'];

        $model = $this->choice(
            'What model do you want to transfer?',
            $models,
            0,
            $maxAttempts = null,
            $allowMultipleSelections = false
        );

        $instance = $this->getModel($model);

        $this->line('We are going to transfer ' . $instance::count(). ' records in ' . $model . '.');

        if ($this->confirm('Do you wish to continue?')) {
            
            $this->transferData($model);
        }
    }

    public function getModel($modelName)
    {
        $class =  'App\\Models\\' . $modelName;

        $instance = new $class;

        return $instance;
    }

    public function transferData($modelName)
    {
        $model = $this->getModel($modelName);

        $bar = $this->output->createProgressBar($model->count());
 
        $bar->start();
        
        foreach ($model->get() as $item) {
            //$this->performTask($item);

            //$this->line($item->id);
        
            $bar->advance();
        }
        
        $bar->finish();
        
    }

}
