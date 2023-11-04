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

    protected $appends = [
         'status', 
         'record_type', 
         'file_learning_documentation_url',
         'primary_parent'
    ];

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

    public function legacies()
    {
        return $this->hasMany(Legacy::class);
    }

    public function appStatus()
    {
        return $this->hasOne(ApplicationStatus::class);
    }

    public function getPrimaryParentAttribute()
    {
        $primaryParent = $this->account->primaryParent;

        if(empty($primaryParent)){
            $primaryParent = $this->account->parents()->first();
        }

        return $primaryParent;
    }

    public function archive()
    {
        return $this->hasOne(ApplicationArchive::class)->latest();
    }

    public function getRecordTypeAttribute()
    {
        return $this->record_type_id ? RecordType::fromValue($this->record_type_id)?->description : '';
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

    public function isSubmitted()
    {
        return $this->appStatus?->application_submitted;
    }

    public function scopeIncomplete($query)
    {
        return $query->whereHas('appStatus', function($statusQuery){
            $statusQuery->whereNull('application_submitted')->orWhere('application_submitted', false);
        });
    }

    public function scopeHasNotifications($query)
    {
        return $query->whereHas('appStatus', function($statusQuery){
            $statusQuery->whereNotNull('application_submitted');
        });
    }


    public function familyMatrix()
    {
        return [

        ];
    }

    public function supplementalRecommendationRequest()
    {
        return $this->hasMany(SupplementalRecommendationRequest::class);
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

    public function getFileLearningDocumentationUrlAttribute()
    {
        $files = $this->file_learning_documentation;
        if(!empty($files)){
            if( count($files) ){
                $url = [];
                foreach($files as $file)
                {
                    $url[] = url('storage', $file);
                }

                return $url;
            }
        }

        return [];
    }

    public static function optionList()
    {
        $array = [];
        foreach(Application::with('student')->get() as $app)
        {
            if($app->student){
                $array[$app->id] = $app->student->first_name . ' ' . $app->student->last_name;
            }
            
        }

        return $array;
    }
}
