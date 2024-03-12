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
        AppVariable::firstOrCreate(
            ['config' => 'academic_school_year'],
            ['value' => '2023 - 2024']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'class_year'],
            ['value' => '2027']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'notification_date'],
            ['value' => 'March 6, 2024']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'acceptance_deadline_date'],
            ['value' => '6:00 am PDT on March 22, 2024']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'registration_start_date'],
            ['value' => 'March 16, 2024 at 12 am PDT']
        );

        AppVariable::firstOrCreate(
            ['config' => 'registration_end_date'],
            ['value' => '6:00 am PDT on March 22, 2024']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'number_of_applicants'],
            ['value' => '1,290']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'challenge_test_date'],
            ['value' => 'April 2, 2024']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'cat_camp_title'],
            ['value' => 'Cat Camp (available Tuesday, March 21, 2023 at 10:00 am PT)']
        );

        AppVariable::firstOrCreate(
            ['config' => 'cat_camp_description'],
            ['value' => '<-- insert description -->']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'cat_camp_url'],
            ['value' => 'https://www.siprograms.com/camps/cat-camp']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'rising_9th_grade_title'],
            ['value' => 'Rising 9th Grade Summer Classes (available Tuesday, March 21, 2023 at 10:00 am PT)']
        );

        AppVariable::firstOrCreate(
            ['config' => 'rising_9th_grade_description'],
            ['value' => '<-- insert description -->']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'rising_9th_grade_url'],
            ['value' => 'https://www.siprograms.com/academics/9th-grade']
        );

        AppVariable::firstOrCreate(
            ['config' => 'frosh_athletics_title'],
            ['value' => '<-- insert title -->']
        );

        AppVariable::firstOrCreate(
            ['config' => 'frosh_athletics_description'],
            ['value' => '<-- insert description -->']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'frosh_athletics_url'],
            ['value' => 'https://www.siprograms.com/academics/9th-grade']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'health_form_due_date'],
            ['value' => '2024-03-25', 'display_value' => 'March 25, 2024']
        );

        AppVariable::firstOrCreate(
            ['config' => 'medical_form_url'],
            ['value' => 'https://resources.finalsite.net/images/v1674767044/siprep/t6goeoxvhp5mj2nzsgcu/MedicalClearanceFormTemplate.pdf']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'family_id_start_date'],
            ['value' => '2024-03-25', 'display_value' => 'March 25, 2024']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'family_id_end_date'],
            ['value' => '2024-03-25', 'display_value' => 'March 25, 2024']
        );
        
        AppVariable::firstOrCreate(
            ['config' => 'family_id_url'],
            ['value' => 'https://www.familyid.com/st-ignatius-college-preparatory/2023-24-athletic-registration']
        );
        
    }
}
