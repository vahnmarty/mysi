<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMatrix extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'family_matrix';

    protected $appends = ['full_name'];

    public function guardian()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function getFullNameAttribute()
    {
        if($this->parent_id){
            return $this->guardian->first_name . ' ' . $this->guardian->last_name;
        }

        return $this->child->first_name . ' ' . $this->child->last_name;
    }
}
