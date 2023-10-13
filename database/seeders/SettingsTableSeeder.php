<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           
    }

    public function create($config, $description, $value = null, $form_type = null)
    {
        $setting = Setting::where('config', $config)->first();

        if($setting){

            if(empty($setting->value)){
                $setting->value = $value;;
                $setting->save();
            }
        }else{

            $setting = Setting::firstOrCreate([
                'config' => $config,
                'description' => $description,
                'value' => $value,
                'form_type' => $form_type
            ]);
        }
    }
}
