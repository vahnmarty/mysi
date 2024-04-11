<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentStudentFinancialAid extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function student()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }
}
