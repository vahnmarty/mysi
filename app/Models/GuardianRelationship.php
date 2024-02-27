<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuardianRelationship extends Model
{
    use HasFactory;

    protected $appends = ['full_name', 'partner_full_name'];

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(Parents::class);
    }

    public function partner()
    {
        return $this->belongsTo(Parents::class, 'partner_id');
    }
    

    public function getFullNameAttribute()
    {
        return $this->parent->getFullName();
    }

    public function getPartnerFullNameAttribute()
    {
        return $this->partner->getFullName();
    }
}
