<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FamilyDynamic extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['full_name', 'relationship_full_name'];


    public function source()
    {
        if($this->model_type == Parents::class){
            return $this->belongsTo(Parents::class, 'model_id');
        }

        if($this->model_type == Child::class){
            return $this->belongsTo(Child::class, 'model_id');
        }
    }

    public function related()
    {
        if($this->related_type == Parents::class){
            return $this->belongsTo(Parents::class, 'related_id');
        }

        if($this->related_type == Child::class){
            return $this->belongsTo(Child::class, 'related_id');
        }
    }

    public function getFullName()
    {
        return $this->source?->getFullName();
    }

    public function getFullNameAttribute()
    {
        return $this->getFullName();
    }

    public function getRelationshipFullName()
    {
        return $this->related?->getFullName();
    }

    public function getRelationshipFullNameAttribute()
    {
        return $this->getRelationshipFullName();
    }
}
