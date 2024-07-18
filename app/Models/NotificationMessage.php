<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationMessage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'content' => 'json',
        'financial_aid_content' => 'json',
        'faq_content' => 'json',
        'claver_award_content' => 'json',
        'product_design_content' => 'json'
    ];

    public static function boot()
    {
        parent::boot();
        
        self::creating(function ($model) {
            $model->uuid = (string) \Str::uuid();
        });
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
