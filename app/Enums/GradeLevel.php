<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class GradeLevel extends Enum
{
    use EnumCustomTrait;
    
    // Dropdown options are:  Pre-kindergarten, Kindergarten, 1, 2, 3, 4, 5, 6, 7, 8, Freshman, Sophomore, Junior, Senior, College, Post HS/College
    const PreKindergarten = 'Pre-kindergarten';
    const Kindergarten = 'Kindergarten';
    const Grade1 = '1';
    const Grade2 = '2';
    const Grade3 = '3';
    const Grade4 = '4';
    const Grade5 = '5';
    const Grade6 = '6';
    const Grade7 = '7';
    const Grade8 = '8';
    const Freshman = '9';
    const Sophomore = '10';
    const Junior = '11';
    const Senior = '12';
    const College = 'College';
    const PostCollege = 'Post HS/College';

    public static function gradeLevelValues()
    {
        $limit = range(1,12);
        $arr = [];
        $current = self::asSameArray();

        foreach($current as $label => $value){
            if(is_numeric($value)){
                $arr[] = (int) $value;
            }
        }

        return $arr;
    }

    public static function forTransfer()
    {
        return [
            10 => 10,
            11 => 11,
        ];
    }

}
