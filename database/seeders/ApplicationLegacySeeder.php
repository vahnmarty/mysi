<?php

namespace Database\Seeders;

use App\Models\Legacy;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ApplicationLegacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $legacies = Legacy::with('account.applications')->whereNull('application_id')->get();

        foreach($legacies as $legacy)
        {
            $application = $legacy->account->applications()->first();

            if(!empty( $application ) )
            {
                $legacy->application_id = $application->id;
                $legacy->save();
            }
        }


    }
}
