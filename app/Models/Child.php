<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Child extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function application()
    {
        return $this->hasOne(Application::class);
    }

    public function submitted()
    {
        if($this->application)
        {
            if($this->application->appStatus?->application_submitted){
                return true;
            }
        }

        return false;
    }
}
