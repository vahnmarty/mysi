<?php

namespace Database\Seeders;

use Excel;
use App\Imports\SchoolsImport;
use Illuminate\Database\Seeder;
use App\Imports\SalesforceImport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ImportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Excel::import(new SchoolsImport, 'schools.xlsx');
        Excel::import(new SalesforceImport, 'Salesforce_Data.xlsx');
    }
}
