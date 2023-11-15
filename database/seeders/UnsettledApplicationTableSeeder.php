<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnsettledApplication;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnsettledApplicationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = database_path('imports/AccountIDs.csv');

        $handle = fopen($file, 'r');

        // Skip the first line
        fgetcsv($handle);

        // Loop through the remaining lines
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            
            $id = $data[0];

            UnsettledApplication::firstOrCreate(['account_id' => $id]);
        }
    }
}
