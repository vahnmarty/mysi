<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationStatus extends Model
{
    use HasFactory;

    protected $table = 'application_status';
    
    protected $guarded = [];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function withHonors()
    {
        return $this->honors_math || $this->honors_english || $this->honors_bio;
    }

    public function withFA()
    {
        return !empty($this->financial_aid);
    }
}
