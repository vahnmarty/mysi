<?php

namespace App\Console\Commands;

use Excel;
use App\Models\School;
use App\Imports\SchoolsImport;
use Illuminate\Console\Command;

class ImportSchoolExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:school {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import School';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->import();

        // if($this->option('force')){
        //     $this->import();
        // }
        // else{
        //     if ($this->confirm('Do you wish to continue? This will clear the current schools list.')) {
        //         $this->import();
        //     }
        // }
        
    }

    public function import()
    {
        School::truncate();
            
        Excel::import(new SchoolsImport, 'List_of_Bay_Area_Schools (4).xlsx');

        $this->info('Schools: ' .  School::count() . ' records.');
    }
}
