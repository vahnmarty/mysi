<?php

namespace App\Models;

use App\Enums\AddressLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parents extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    //protected $appends = ['full_name'];

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullNameAttribute()
    {
        return $this->getFullName();
    }

    public function scopeFromAccount($query, $accountId = null){
        $account_id = $accountId ?? accountId();
        return $query->where('account_id', $account_id);
    }
    
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function scopeFromPrimaryAddress($query)
    {
        return $query->where('address_location', AddressLocation::PrimaryAddress);
    }

    // public function relationships(): MorphMany
    // {
    //     return $this->morphMany(FamilyDynamic::class, 'related');
    // }

    public function relationships()
    {
        return $this->hasMany(FamilyDynamic::class, 'model_id')->where('model_type', self::class);
    }
}
