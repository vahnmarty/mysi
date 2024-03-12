<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Club extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];

    public static function getNameArray()
    {
        $array = [];
        foreach(self::get() as $club)
        {
            $array[$club->name] = $club->name;
        }   

        return $array;
    }
}
