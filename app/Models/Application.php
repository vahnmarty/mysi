<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Application extends Model
{
    use HasFactory;

    protected $guarded = [] ;

    public static function boot()
    {
        parent::boot();
        
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function addresses()
    {
        return $this->hasManyThrough(Address::class, Account::class);
    }
}
