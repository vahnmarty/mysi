<?php

namespace App\Console\Commands;

use Mail;
use App\Mail\SampleMail;
use Illuminate\Console\Command;

class SendSampleEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-sample-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Sample Email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $message = $this->ask('Type message: (Optional)');

        $email = $this->argument('email');

        Mail::to($email)->send(new SampleMail($message));
    }
}
