<?php

namespace App\Models;

use Str;
use App\Enums\RecordType;
use App\Enums\CandidateDecisionType;
use App\Enums\NotificationStatusType;
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
        return $this->hasOne(Payment::class)->where('payment_type', 'AppFee')->latest();
    }

    public function applicationFee()
    {
        return $this->hasOne(Payment::class)->where('payment_type', 'AppFee')->latest();
    }

    public function registration()
    {
        return $this->hasOne(Registration::class);
    }

    public function scopeHasPromoCode( $query )
    {
        return $query->whereHas('payments', function($pQuery){
            $pQuery->whereNotNull('promo_code');
        });
    }

    public function scopeNoTransaction( $query )
    {
        return $query->whereHas('payments', function($pQuery){
            $pQuery->whereNull('transaction_id');
        });
    }

    public function isPaid()
    {
        foreach($this->payments as $payment)
        {
            if($payment->transaction_id && $payment->total_amount > 0){
                return true;
            }

            if($payment->promo_code == 'App0'){
                return true;
            }
        }

        return false;
    }

    
    public function scopeUnpaid( $query )
    {
        return $query->whereHas('payments', function($pQuery){
            $pQuery->whereNull('transaction_id')->orWhere('total_amount','<=' ,0);
        });
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

    public function hasRegistered()
    {
        return $this->registration;
    }

    public function applicationAccepted()
    {
        return $this->appStatus->application_status == NotificationStatusType::Accepted;
    }

    public function enrolled()
    {
        return $this->appStatus->candidate_decision_status == CandidateDecisionType::Accepted;
    }

    public function notAccepted()
    {
        return $this->appStatus->application_status == NotificationStatusType::NotAccepted;
    }

    public function fa_acknowledged():bool | null
    {
        return !empty($this->appStatus->fa_acknowledged_at);
    }

    public function declined()
    {
        $decision = $this->appStatus?->candidate_decision;

        if(!is_null($decision)){
            return $decision == false;
        }

        return false;
    }

    public function waitlisted()
    {
        $decision = $this->appStatus?->application_status;

        return $decision == NotificationStatusType::WaitListed;
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



    public function supplementalRecommendationRequest()
    {
        return $this->hasMany(SupplementalRecommendationRequest::class);
    }


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

    public static function isEnabled()
    {
        $freshmen_application_start_date = notification_setting('freshmen_application_start_date');
        $freshmen_application_end_date = notification_setting('freshmen_application_hard_close_date');

        $app_start_date = $freshmen_application_start_date->value;
        $app_end_date = $freshmen_application_end_date->value;

        return now()->gte($app_start_date) && now()->lt($app_end_date) || empty($app_start_date)  || empty($app_end_date);
    }

    public function notificationMessages()
    {
        return $this->hasMany(NotificationMessage::class);
    }

    public function notificationMessage()
    {
        return $this->hasOne(NotificationMessage::class)->latest();
    }

    public function canEnroll()
    {
        $condition =  $this->applicationAccepted() && !$this->hasRegistered() && !$this->enrolled() && !$this->declined() && !$this->waitlisted();

        if($condition){
            if($this->appStatus->financial_aid){
                $condition = $this->fa_acknowledged();
            }
        }

        return $condition;
    }

    public function waitlistRemoved()
    {
        return $this->appStatus?->candidate_decision == 3;
    }

    public function classList(): array
    {
        $appStatus = $this->appStatus;
        $array = [];

        if($appStatus->english_class){
            $array[] = $appStatus->english_class;
        }

        if($appStatus->math_class){
            $array[] = $appStatus->math_class;
        }

        if($appStatus->bio_class){
            $array[] = $appStatus->bio_class;
        }

        return $array;
        
    }
}
