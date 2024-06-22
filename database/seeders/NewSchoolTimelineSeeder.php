<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NewSchoolTimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create('hspt_scores_start_date', 'HSPT Scores Start Date', null, 'datetime');
        $this->create('hspt_scores_end_date', 'HSPT Scores End Date', null, 'datetime');
        $this->create('transfer_application_start_date', 'Transfer Application Start Date', null, 'datetime');
        $this->create('transfer_application_end_date', 'Transfer Application End Date', null, 'datetime');
        $this->create('re_registration_start_date', 'Re-Registration Start Date', null, 'datetime');
        $this->create('re_registration_end_date', 'Re-Registration End Date', null, 'datetime');

        $this->create('upload_accommodation_document_start_date', 'Upload Accommodations Start Date', null, 'datetime');
        $this->create('upload_accommodation_document_end_date', 'Upload Accommodations End Date', null, 'datetime');

    }

    public function create($config, $description, $value = null, $form_type = null)
    {
        $setting = NotificationSetting::where('config', $config)->first();

        if($setting){

            if(empty($setting->value)){
                $setting->value = $value;
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
