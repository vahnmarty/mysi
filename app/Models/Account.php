<?php

namespace App\Models;

use Auth;
use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function getAccountId()
    {
        return Auth::user()->account_id;
    }
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function primaryAddress()
    {
        return $this->hasOne(Address::class)->where('address_type', AddressType::PrimaryAddress);
    }

    public function parents()
    {
        return $this->hasMany(Parents::class);
    }

    public function primaryParent()
    {
        return $this->hasOne(Parents::class)->where('is_primary', true);
    }

    public function firstParent()
    {
        return $this->hasOne(Parents::class);
    }

    public function guardians()
    {
        return $this->hasMany(Parents::class);
    }

    public function children()
    {
        return $this->hasMany(Child::class);
    }

    public function legacies()
    {
        return $this->hasMany(Legacy::class);
    }

    // With Trashed

    public function all_addresses()
    {
        return $this->hasMany(Address::class)->withTrashed();
    }

    public function all_parents()
    {
        return $this->hasMany(Parents::class)->withTrashed();
    }

    public function all_guardians()
    {
        return $this->hasMany(Parents::class)->withTrashed();
    }

    public function all_children()
    {
        return $this->hasMany(Child::class)->withTrashed();
    }

    public function all_legacies()
    {
        return $this->hasMany(Legacy::class)->withTrashed();
    }


    public function hasEnrolled()
    {
        foreach($this->applications as $app)
        {
            if($app->status == 'Enrolled'){
                return true;
            }
        }

        return false;
    }
}
