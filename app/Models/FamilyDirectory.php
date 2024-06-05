<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyDirectory extends Model
{
    use HasFactory;

    protected $table = 'si_family_directory';

    protected $guarded =[];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
