<?php

namespace App\Models;

//use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmail;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\Auth\ForgotUsername as ForgotUsernameNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_id',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'phone',
        'phone_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function sendForgotUsernameNotification()
    {
        $this->notify(new ForgotUsernameNotification());
    }

    public function passwords()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    public function addPasswordHistory()
    {
        $this->passwords()->create([
            'password' => $this->password
        ]);
    }

    public function checkPasswordTaken($newPassword)
    {
        foreach($this->passwords as $history)
        {
            if(\Hash::check($newPassword, $history->password)){
                return true;
            }
        }

        return false;
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function emails()
    {
        return $this->hasMany(EmailHistory::class);
    }

    public function addEmailHistory()
    {
        $this->emails()->create([
            'email' => $this->email
        ]);
    }

    public function emailRequests()
    {
        return $this->hasMany(EmailRequest::class);
    }

    public function getUserName(Model | Authenticatable $user): string

    {

        if ($user instanceof HasName) {

            return $user->getFilamentName();

        }



        return $user->getAttributeValue('first_name');

    }

    public function getNameAttribute()
    {
        return $this->first_name  . ' ' . $this->last_name;
    }

    public function canAccessFilament(): bool
    {
        return $this->hasRole('admin');
    }
}
