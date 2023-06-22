<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!User::where('email', config('settings.si.admissions.email'))->exists())
        {
            $user = new User;
            $user->first_name = 'SI';
            $user->last_name = 'Admission';
            $user->email = config('settings.si.admissions.email');
            $user->password = bcrypt('password');
            $user->email_verified_at = now();
            $user->save();

            $user->assignRole('admin');
        }
        


    }
}
