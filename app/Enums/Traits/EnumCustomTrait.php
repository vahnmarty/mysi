<?php

namespace App\Enums\Traits;

trait EnumCustomTrait{

    public static function asSameArray(): array
    {
        $array = static::asArray();
        $selectArray = [];

        foreach ($array as $value) {
            $selectArray[$value] = $value;
        }

        return $selectArray;
    }
    
}