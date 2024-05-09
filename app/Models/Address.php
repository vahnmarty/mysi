<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function getFullAddress()
    {
        return $this->address . ', ' . $this->city . ', ' . $this->state . ' ' . $this->zip_code;
    }

    public function getShortAddress()
    {
        return $this->city . ', ' . $this->state;
    }
}
