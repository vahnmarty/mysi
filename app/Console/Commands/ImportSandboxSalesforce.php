<?php

namespace App\Console\Commands;

use Excel;
use App\Imports\ChildrenImport;
use Illuminate\Console\Command;
use App\Imports\SalesforceImport;
use App\Imports\SomeChildrenImport;

class ImportSandboxSalesforce extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sandbox {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from Salesforce Sandbox';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->import();
    }

    public function import()
    {
        //$import = new SalesforceImport();
        //$import->import('Salesforce_Data.xlsx');

        Excel::import(new SalesforceImport, 'Salesforce_Data.xlsx');


        // foreach ($import->failures() as $failure) {
        //     $this->error($failure->row());
        //     $this->error($failure->attribute());
        //     $this->error($failure->errors());
        //     $this->error($failure->values());
        //     // $failure->row(); // row that went wrong
        //     // $failure->attribute(); // either heading key (if using heading row concern) or column index
        //     // $failure->errors(); // Actual error messages from Laravel validator
        //     // $failure->values(); // The values of the row that has failed.
        // }

        //Excel::import(new SalesforceImport, 'Salesforce_Data.xlsx');
    }
}
