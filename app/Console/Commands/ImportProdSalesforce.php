<?php

namespace App\Console\Commands;

use Excel;
use App\Models\Child;
use App\Models\Account;
use App\Models\Address;
use App\Models\Parents;
use App\Imports\AccountsImport;
use App\Imports\ChildrenImport;
use Illuminate\Console\Command;
use App\Imports\AddressesImport;
use App\Imports\LiveAddressImport;
use App\Imports\LiveChildrenImport;

class ImportProdSalesforce extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:salesforce';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Salesforce';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('Importing Accounts...');
        Excel::import(new AccountsImport, 'Accounts.xlsx');
        $this->info(Account::count() . ' accounts has been imported.');
        $this->newLine();

        $this->warn('Importing Addresses...');
        Excel::import(new LiveAddressImport, 'Addresses.xlsx');
        $this->info(Address::count() . ' has been imported.');
        $this->newLine();

        $this->warn('Importing Children...');
        Excel::import(new LiveChildrenImport, 'Children.xlsx');
        $this->info(Child::count() . ' has been imported.');
        $this->newLine();

        $this->warn('Importing Parents...');
        Excel::import(new LiveParentsImport, 'Parents.xlsx');
        $this->info(Parents::count() . ' has been imported.');
        $this->newLine(3);

        $this->info('Import Done!');
    }
}
