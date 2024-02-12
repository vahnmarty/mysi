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

    public function healthcares()
    {
        return $this->hasMany(Healthcare::class);
    }

    public function emergencyContacts()
    {
        return $this->hasMany(EmergencyContact::class);
    }

    public function accommodations()
    {
        return $this->hasMany(Accommodation::class);
    }

    public function magisPrograms()
    {
        return $this->hasMany(MagisProgram::class);
    }

    public function coursePlacements()
    {
        return $this->hasMany(coursePlacement::class);
    }

    public function getParentsName($withSalutation = false)
    {
        if($this->parents()->count() == 1){
            $firstParent = $this->parents()->first();

            if($withSalutation){
                return $firstParent->salutation . ' ' . $firstParent->first_name ;
            }
            return $firstParent->first_name;
        }

        if($this->parents()->count() == 2){
            $parents = $this->parents;
            $string = '';

            foreach($this->parents as $i => $parent){

                if($withSalutation){
                    $string .= $parent->salutation . ' ' . $parent->last_name;
                }else{
                    $string .=  $parent->first_name . ' ' . $parent->last_name;
                }

                if($i != 1){
                    $string .= ' and ';
                }
                
            }

            return $string;
        }
    }

    public function registration()
    {
        return $this->hasOne(Registration::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function hasRegisteredStudent()
    {
        return false;
        foreach($this->applications as $app){
            if($app?->appStatus->registration_completed){
                return true;
                break;
            }
        }

        return false;;
    }
}
