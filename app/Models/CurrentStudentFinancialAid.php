<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CurrentStudentFinancialAid extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'fa_contents' => 'json',
    ];

    public static function boot()
    {
        parent::boot();
        
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

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

    public function fa_read()
    {
        return $this->read_at ? true : false;
    }

    public function fa_acknowledged()
    {
        return $this->fa_acknowledged_at ? true : false;
    }
}
