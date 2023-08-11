<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FakeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $max = 300;
        foreach(range(1, $max) as $i)
        {
            $name = fake()->name() . ' ' . fake()->name();
            $account = Account::firstOrCreate(['name' => 'The ' . $name . ' Family']);
        }
    }
}
