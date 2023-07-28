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
    protected $signature = 'import:school';

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
        if ($this->confirm('Do you wish to continue? This will clear the current schools list.')) {
 
            School::truncate();
            
            Excel::import(new SchoolsImport, 'si_schools1.xlsx');
            Excel::import(new SchoolsImport, 'si_schools2.xlsx');

            $this->info('Schools: ' .  School::count() . ' records.');
        }
    }
}
