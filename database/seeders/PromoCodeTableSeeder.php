<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PromoCodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PromoCode::firstOrCreate([
            'code' => 'App100',
            'amount' => 100
        ]);

        PromoCode::firstOrCreate([
            'code' => 'App75',
            'amount' => 75
        ]);

        PromoCode::firstOrCreate([
            'code' => 'App50',
            'amount' => 50
        ]);

        PromoCode::firstOrCreate([
            'code' => 'App40',
            'amount' => 40
        ]);

        PromoCode::firstOrCreate([
            'code' => 'App25',
            'amount' => 25
        ]);

        PromoCode::firstOrCreate([
            'code' => 'App0',
            'amount' => 0
        ]);
    }
}
