<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RacialType extends Enum
{
    // Checkbox options are:  Asian, Black/African American, Latino/Latina/Latinx, Native American/Indigenous, Polynesian or Pacific Islander, White
    const Asian = 'Asian';
    const Black = 'Black/African American';
    const Latin = 'Latino/Latina/Latinx';
    const Native = 'Native American/Indigenous';
    const Polynesian = 'Polynesian or Pacific Islander';
    const White = 'White';

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
