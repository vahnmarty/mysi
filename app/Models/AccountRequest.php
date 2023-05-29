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

        static::created(function (AccountRequest $account) {
            Mail::to($account->email)->send(new AccountRequested($account));
        });
    }

    public function expired()
    {
        return now() >= $this->expires_at;
    }
}
