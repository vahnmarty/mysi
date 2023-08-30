<?php

namespace App\Models;

use Str;
use App\Enums\RecordType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [] ;

    protected $appends = ['status', 'record_type'];

    protected $casts = [
        'file_learning_documentation' => 'array'
    ];

    public static function boot()
    {
        parent::boot();
        
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function student()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function addresses()
    {
        return $this->hasManyThrough(Address::class, Account::class);
    }

    public function matrix()
    {
        return $this->hasMany(FamilyMatrix::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function appStatus()
    {
        return $this->hasOne(ApplicationStatus::class);
    }

    public function archive()
    {
        return $this->hasOne(ApplicationArchive::class);
    }

    public function getRecordTypeAttribute()
    {
        return RecordType::fromValue($this->record_type_id)->description;
    }

    public function getStatusAttribute()
    {
        if($this->appStatus?->application_submitted){
            return 'Submitted';
        }

        return '';
    }

    public function scopeSubmitted($query)
    {
        return $query->whereHas('appStatus', function($statusQuery){
            $statusQuery->where('application_submitted', true);
        });
    }

    public function familyMatrix()
    {
        return [

        ];
    }

    // public function current_school()
    // {
    //     return $this->hasManyThrough(
    //         School::class,
    //         Child::class,
    //         'current_school',
    //         'name'
    //     );
        
    // }
}
