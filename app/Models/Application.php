<?php

namespace App\Models;

use Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;
    use SoftDeletes;

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
