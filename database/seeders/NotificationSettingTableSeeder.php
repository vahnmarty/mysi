<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NotificationSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create('academic_year_applying_for', 'Academic Year Applying For', null, 'range_year');
        $this->create('freshmen_application_start_date', 'Freshmen Application Start Date', null, 'date');
        $this->create('freshmen_application_soft_close_date', 'Freshmen Application Soft Close Date', null, 'date');
        $this->create('freshmen_application_hard_close_date', 'Freshmen Application Hard Close Date', null, 'date');
        $this->create('supplemental_recommendation_start_date', 'Supplemental Recommendation Start Date', null, 'date');
        $this->create('supplemental_recommendation_end_date', 'Supplemental Recommendation End Date', null, 'date');
        $this->create('notification_start_date', 'Notification Start Date', null, 'date');
        $this->create('notification_end_date', 'Notification End Date', null, 'date');
        $this->create('registration_start_date', 'Registration Start Date', null, 'date');
        $this->create('registration_end_date', 'Registration End Date', null, 'date');
        $this->create('test_placement_notification_start_date', 'Test Placement Notification Start Date', null, 'date');
        $this->create('test_placement_notification_end_date', 'Test Placement Notification End Date', null, 'date');
        $this->create('transfer_student_application_start_date', 'Transfer Student Application Start Date', null, 'date');
        $this->create('transfer_student_application_end_date', 'Transfer Student Application End Date', null, 'date');     
    }

    public function create($config, $description, $value = null, $form_type = null)
    {
        $setting = NotificationSetting::where('config', $config)->first();

        if($setting){

            if(empty($setting->value)){
                $setting->value = $value;;
                $setting->save();
            }
        }else{

            $setting = NotificationSetting::firstOrCreate([
                'config' => $config,
                'title' => $description,
                'value' => $value,
                'form_type' => $form_type
            ]);
        }
    }
}
