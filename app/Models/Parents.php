<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parents extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $appends = ['full_name'];

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
}
