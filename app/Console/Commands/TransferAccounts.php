<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Child;
use App\Models\Legacy;
use App\Models\Account;
use App\Models\Address;
use App\Models\Parents;
use App\Models\Activity;
use App\Models\Application;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:transfer-accounts';

    public $db2 = 'mysql2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer Data from Prod to Sandbox';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->newLine();

        $this->line('Transferring ' . Account::count(). ' accounts to ' . config('database.connections.mysql2.database'));

        if ($this->confirm('Do you wish to continue?')) {
            
            $this->transferAccounts();

            $this->newLine(3);
            $this->info('Sync completed.');
            
        }

    }


    public function transferAccounts()
    {
        DB::transaction(function () {

            $bar = $this->output->createProgressBar(Account::count());
 
            $bar->start();

            foreach (Account::get() as $srcAccount) 
            {   
                
                # Account
                $accountClass = new Account;
                $newAccount = $accountClass->setConnection('mysql2')
                        ->where('account_name', $srcAccount->account_name)
                        ->first();

                if(!$newAccount){
                    $data = $srcAccount->makeHidden($this->salesforceFields())->toArray();

                    $newAccount = $accountClass->setConnection('mysql2');
                    $newAccount->fill($data);
                    $newAccount->save();
                }
                
                $accountId = $newAccount->id;
                
                # Users
                $newAccount->users()->delete();

                foreach($srcAccount->users as $user)
                {
                    $data = $user->makeHidden($this->salesforceFields())->toArray();

                    try {
                        $newUser = new User;
                        $newUser->setConnection($this->db2);
                        $newUser->fill($data);
                        $newUser->account_id = $newAccount->id;
                        $newUser->saveQuietly();
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }

                # Parents
                $newAccount->parents()->delete();

                foreach($srcAccount->parents as $parent)
                {
                    $data = $parent->makeHidden($this->salesforceFields())->toArray();

                    $newParent = new Parents;
                    $newParent->setConnection($this->db2);
                    $newParent->fill($data);
                    $newParent->account_id = $newAccount->id;
                    $newParent->saveQuietly();
                }

                # Children
                $newAccount->children()->delete();

                foreach($srcAccount->children as $child)
                {
                    $data = $child->makeHidden($this->salesforceFields())->toArray();

                    $newChild = new Child;
                    $newChild->setConnection($this->db2);
                    $newChild->fill($data);
                    $newChild->account_id = $newAccount->id;
                    $newChild->saveQuietly();
                }

                # Address
                $newAccount->addresses()->delete();

                foreach($srcAccount->addresses as $address)
                {
                    $data = $address->makeHidden($this->salesforceFields())->toArray();

                    $newAddress = new Address;
                    $newAddress->setConnection($this->db2);
                    $newAddress->fill($data);
                    $newAddress->account_id = $newAccount->id;
                    $newAddress->saveQuietly();
                }

                # Applications
                foreach($newAccount->applications()->get() as  $app)
                {
                    $app->legacies()->delete();
                    $app->activities()->delete();
                }

                $newAccount->applications()->delete();

                foreach($srcAccount->applications()->get() as $application)
                {
                    $data = $application->makeHidden($this->salesforceFields())->toArray();

                    $keysToRemove = ['primary_parent', 'status', 'record_type', 'file_learning_documentation_url', 'file_learning_documentation', 'app_status', 'account'];

                    $newArray = array_diff_key($data, array_flip($keysToRemove));

                    $newApp = new Application;
                    $newApp->setConnection($this->db2);
                    $newApp->fill($newArray);
                    $newApp->account_id = $newAccount->id;
                    $newApp->saveQuietly();
                    $newApp->save();

                    # Legacies
                    foreach($application->legacies as $legacy)
                    {
                        $data = $legacy->makeHidden($this->salesforceFields())->toArray();

                        $newLegacy = new Legacy;
                        $newLegacy->setConnection($this->db2);
                        $newLegacy->fill($data);
                        $newLegacy->account_id = $newAccount->id;
                        $newLegacy->saveQuietly();
                    }

                    # Activities
                    foreach($application->activities as $activity)
                    {
                        $data = $activity->makeHidden($this->salesforceFields())->toArray();

                        $newActivity = new Activity;
                        $newActivity->setConnection($this->db2);
                        $newActivity->fill($data);
                        $newActivity->saveQuietly();
                    }
                }

            
                $bar->advance();
            }

            $bar->finish();
        });
        
        
        
    }

    public function salesforceFields()
    {
        return [
            'id', 'sf_account_id', 'record_type_id', 'sf_residence_id', 'created_at', 'updated_at',
            'sf_contact_id', 'sf_residence_id', 'sf_legacy_id', 'sf_application_id', 'sf_activity_id'
        ];
    }

}
