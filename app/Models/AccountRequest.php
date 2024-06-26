<?php

namespace App\Models;

use Str;
use Mail;
use App\Mail\AccountRequested;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountRequest extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'token', 'expires_at', 'activated_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'activated_at' => 'datetime'
    ];

    protected static function booted(): void
    {
        static::creating(function ($account) {
            $account->token = Str::uuid();
        });
    }

    public function expired()
    {
        return $this->expires_at;
    }

    public function activated()
    {
        return $this->activated_at;
    }
}
