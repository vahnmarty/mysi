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
}
