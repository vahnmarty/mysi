<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\Address;
use Illuminate\Bus\Queueable;
use App\Models\FamilyDirectory;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessFamilyDirectory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Account $account;

    /**
     * Create a new job instance.
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $account = $this->account;

        foreach($account->children as $child)
        {
            $address = Address::where('account_id', $account->id)->where('address_type', $child->address_location)->first();
            
            FamilyDirectory::create([
                'account_id' => $child->account_id,
                'name' => $child->getFullName(),
                'type' => 'STUDENT',
                'share_email' => $child->share_personal_email,
                'email' => $child->personal_email,
                'share_phone' => $child->share_mobile_phone,
                'phone' => $child->mobile_phone,
                'share_full_address' => $child->share_full_address,
                'address' => $child->share_full_address ? $address?->getFullAddress() : $address?->getShortAddress()
            ]);
        }

        foreach($account->parents as $parent)
        {
            $address = Address::where('account_id', $account->id)->where('address_type', $parent->address_location)->first();
            
            FamilyDirectory::create([
                'account_id' => $account->id,
                'name' => $parent->getFullName(),
                'type' => 'PARENT',
                'share_email' => $child->share_personal_email,
                'email' => $parent->personal_email,
                'share_phone' => $parent->share_mobile_phone,
                'phone' => $parent->mobile_phone,
                'share_full_address' => $parent->share_full_address,
                'address' => $parent->share_full_address ? $address?->getFullAddress() : $address?->getShortAddress()
            ]);
        }
    }
}
