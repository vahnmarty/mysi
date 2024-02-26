<?php

namespace Database\Seeders;

use App\Models\AppVariable;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AppVariableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppVariable::firstOrCreate([
            'config' => 'cat_camp_title',
            'value' => 'Cat Camp (available Tuesday, March 21, 2023 at 10:00 am PT)',
        ]);

        AppVariable::firstOrCreate([
            'config' => 'cat_camp_url',
            'value' => 'https://www.siprograms.com/camps/cat-camp',
        ]);

        AppVariable::firstOrCreate([
            'config' => 'rising_9th_grade_title',
            'value' => 'Rising 9th Grade Summer Classes (available Tuesday, March 21, 2023 at 10:00 am PT)',
        ]);

        AppVariable::firstOrCreate([
            'config' => 'rising_9th_grade_url',
            'value' => 'https://www.siprograms.com/academics/9th-grade',
        ]);

        AppVariable::firstOrCreate([
            'config' => 'health_form_due_date',
            'value' => '2024-03-25',
            'display_value' => 'March 25, 2024'
        ]);

        AppVariable::firstOrCreate([
            'config' => 'family_id_start_date',
            'value' => '2024-03-25',
            'display_value' => 'March 25, 2024'
        ]);

        AppVariable::firstOrCreate([
            'config' => 'family_id_end_date',
            'value' => '2024-03-25',
            'display_value' => 'March 25, 2024'
        ]);

        AppVariable::firstOrCreate([
            'config' => 'family_id_url',
            'value' => 'https://www.familyid.com/st-ignatius-college-preparatory/2023-24-athletic-registration',
        ]);
    }
}
