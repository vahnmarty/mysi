<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplementalRecommendationRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->uuid = \Str::uuid();
        });
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function guardian()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }
}
