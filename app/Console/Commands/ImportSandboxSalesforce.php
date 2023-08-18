<?php

namespace App\Console\Commands;

use Excel;
use Illuminate\Console\Command;
use App\Imports\SalesforceImport;

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
        Excel::import(new SalesforceImport, 'Salesforce_Data.xlsx');
    }
}
