<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class RunMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:hot-migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Running necessary migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Schema::table('children', function (Blueprint $table) {

            if ($this->confirm('Running command: $table->string(\'interest1 - interest2- interest3\')->nullable();')) {
                if (!Schema::hasColumn('children', 'interest1')) {
                    $table->string('interest1', 100)->nullable();
                    $this->info('interest1 is migrated.');
                }else{
                    $this->error('interest1 is alreadty added.');
                }

                if (!Schema::hasColumn('children', 'interest2')) {
                    $table->string('interest2', 100)->nullable();
                    $this->info('interest2 is migrated.');
                }else{
                    $this->error('interest2 is alreadty added.');
                }

                if (!Schema::hasColumn('children', 'interest3')) {
                    $table->string('interest3', 100)->nullable();
                    $this->info('interest3 is migrated.');
                }else{
                    $this->error('interest3 is alreadty added.');
                }
            }

            

            if ($this->confirm('Running command: $table->boolean(\'is_interested_club\')->nullable();')) {
                if (!Schema::hasColumn('children', 'is_interested_club')) {
                    $table->boolean('is_interested_club')->nullable();
                    $this->info('is_interested_club is migrated.');
                }else{
                    $this->error('is_interested_club is alreadty added.');
                }
            }

            if ($this->confirm('Running command: $table->boolean(\'is_interested_sports\')->nullable();')) {
                if (!Schema::hasColumn('children', 'is_interested_sports')) {
                    $table->boolean('is_interested_sports')->nullable();
                    $this->info('is_interested_sports is migrated.');
                }else{
                    $this->error('is_interested_sports is alreadty added.');
                }
            }
            
        });
    }
}
