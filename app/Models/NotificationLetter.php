<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLetter extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function setting()
    {
        return $this->belongsTo(NotificationSetting::class);
    }
}
