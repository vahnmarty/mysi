<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Account extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getAccountId()
    {
        return Auth::user()->account_id;
    }

    public function addresses()
    {
        return $this->hasMany(Address::class)->withTrashed();
    }

    public function parents()
    {
        return $this->hasMany(Parents::class)->withTrashed();
    }

    public function guardians()
    {
        return $this->hasMany(Parents::class)->withTrashed();
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function children()
    {
        return $this->hasMany(Child::class)->withTrashed();
    }

    public function siblings()
    {

    }

    public function legacies()
    {
        return $this->hasMany(Legacy::class);
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
