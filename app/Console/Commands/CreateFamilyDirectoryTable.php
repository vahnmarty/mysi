<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Address;
use App\Models\FamilyDirectory;
use Illuminate\Console\Command;

class CreateFamilyDirectoryTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'si:create-directory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Family Directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Resetting family_directories');

        FamilyDirectory::truncate();

        $accounts = Account::where('current_si_family', true)->get();

        $bar = $this->output->createProgressBar(count($accounts));
 
        $bar->start();

        foreach($accounts as $account)
        {
            foreach($account->children as $child)
            {
                $address = Address::where('account_id', $account->id)->where('address_type', $child->address_location)->first();
                
                if($child->registration)
                {
                    FamilyDirectory::create([
                        'account_id' => $child->account_id,
                        'si_family' => $account->account_name,
                        'full_name' => $child->getFullName(),
                        'contact_type' => 'Student',
                        'graduation_year' => $child->graduation_year,
                        'personal_email' => $child->share_personal_email ? $child->personal_email : null,
                        'mobile_phone' => $child->share_mobile_phone ? $child->mobile_phone : null,
                        'home_address' => $child->share_full_address ? $address?->getFullAddress() : $address?->getShortAddress()
                    ]);
                }

                
            }

            foreach($account->parents as $parent)
            {
                $address = Address::where('account_id', $account->id)->where('address_type', $parent->address_location)->first();
                
                FamilyDirectory::create([
                    'account_id' => $parent->account_id,
                    'si_family' => $account->account_name,
                    'full_name' => $parent->getFullName(),
                    'contact_type' => 'Guardian',
                    'graduation_year' => $parent->graduation_year ?? null,
                    'personal_email' => $parent->share_personal_email ? $parent->personal_email : null,
                    'mobile_phone' => $parent->share_mobile_phone ? $parent->mobile_phone : null,
                    'home_address' => $parent->share_full_address ? $address?->getFullAddress() : $address?->getShortAddress()
                ]);

                
            }

            $bar->advance();

        }

        $bar->finish();

        $this->newLine();
        $this->info('SI Family Directory Finished');
    }
}
