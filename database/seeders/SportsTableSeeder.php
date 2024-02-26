<?php

namespace Database\Seeders;

use App\Models\Sport;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sports = [
            "Baseball",
            "Basketball",
            "Crew",
            "Cross Country",
            "Field Hockey",
            "Flag Football",
            "Football",
            "Golf",
            "Lacrosse",
            "Rugby",
            "Sailing",
            "Soccer",
            "Softball",
            "Swim & Dive",
            "Tennis",
            "Track & Field",
            "Volleyball",
            "Water Polo",
            "None" 
        ];

        foreach ($sports as $name) {
            Sport::firstOrCreate(['name' => $name]);
        }
    }
}
