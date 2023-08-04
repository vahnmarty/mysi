<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiAuthentication;
use Str;

class CreateApiUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create API User';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = new ApiAuthentication;
        

        $name = $this->ask('Name?');

        do {
            $username = $this->ask('Username?');

            if(ApiAuthentication::where('username', $username)->exists()){
                $this->error('This username exists! Try again.');
            }
        } while (ApiAuthentication::where('username', $username)->exists());

        $user->name = $name;
        $user->username = $username;
        $user->password = Str::random(40);
        $user->active = true;
        $user->save();

        $this->newLine();
        
        $this->info('Your password is: ' . $user->password);
        
        
    }
}
