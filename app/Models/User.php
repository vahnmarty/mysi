<?php

namespace App\Models;

//use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmail;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Filament\Models\Contracts\FilamentUser;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\Auth\ForgotUsername as ForgotUsernameNotification;

class User extends Authenticatable implements FilamentUser,MustVerifyEmail
{
    use SoftDeletes;
    
    use HasApiTokens, HasFactory, Notifiable;

    use HasRoles;

    use Impersonate;

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

    public function getFullName()
    {
        return $this->getNameAttribute();
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function canAccessFilament(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('staff');
    }

    public function hasSubmittedApplications()
    {
        return $this->account?->applications()->submitted()->count();
    }


    public function registration()
    {
        return $this->hasOne(Registration::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function students()
    {
        return $this->hasMany(Child::class, Account::class, 'user_id', 'account_id', 'id', 'id');
    }

    public function forceVerifyEmail()
    {
        $this->email_verified_at = now();
        $this->save();
    }

    public function scopeUsers($query)
    {
        return $query->where('id', '!=', 1);
    }

    public function canImpersonate(): bool
    {
        return $this->hasRole('admin');
    }

    public function hasFailedPayment()
    {
        $bool = false;

        $app0 = 'App0';

        $account = Account::find(accountId());

        foreach($account->applications()->submitted()->get() as $app)
        {
            foreach($app->payments as $payment)
            {
                if($payment->total_amount <=0)
                {
                    if($payment->promo_code != $app0)
                    {
                        return true;
                    }
                }

                if(empty($payment->transaction_id))
                {
                    if($payment->promo_code != $app0)
                    {
                        return true;
                    }
                }
            }
        }

        return $bool;
    }

    public function failedApplications()
    {
        $bool = false;

        foreach($this->account->applications()->submitted()->get() as $app)
        {
            foreach($app->payments as $payment)
            {
                if($payment->total_amount <=0)
                {
                    if($payment->promo_code != 'App0')
                    {
                        return true;
                    }
                }
            }
        }
    }
}
