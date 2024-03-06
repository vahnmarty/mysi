<?php

namespace Database\Seeders;

use App\Models\PrepShop;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PrepShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PrepShop::firstOrCreate([
            'date' => '2024-03-16' // Saturday, March 16th
        ], [
            'morning_schedule' => '11:00 am to 1:00 pm',
            'afternoon_schedule' => ''
        ]);

        PrepShop::firstOrCreate([
            'date' => '2024-03-18' // Monday, March 18th
        ], [
            'morning_schedule' => '11:30 am to 1:30 pm',
            'afternoon_schedule' => '3:00 pm to 5:00 pm'
        ]);

        PrepShop::firstOrCreate([
            'date' => '2024-03-19' // Tuesday, March 19th
        ], [
            'morning_schedule' => '11:30 am to 1:30 pm',
            'afternoon_schedule' => '3:00 pm to 5:00 pm'
        ]);

        PrepShop::firstOrCreate([
            'date' => '2024-03-20' // Wednesday, March 20th
        ], [
            'morning_schedule' => '11:30 am to 1:30 pm',
            'afternoon_schedule' => '3:00 pm to 5:00 pm'
        ]);

        PrepShop::firstOrCreate([
            'date' => '2024-03-21' // Thursday, March 21st
        ], [
            'morning_schedule' => '11:30 am to 1:30 pm',
            'afternoon_schedule' => '3:00 pm to 5:00 pm'
        ]);

        PrepShop::firstOrCreate([
            'date' => '2024-03-22' // Friday, March 22nd
        ], [
            'morning_schedule' => '11:30 am to 1:30 pm',
            'afternoon_schedule' => '3:00 pm to 5:00 pm'
        ]);
    }
}
