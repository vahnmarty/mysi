<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RacialType extends Enum
{
    use EnumCustomTrait;
    
    // Checkbox options are:  Asian, Black/African American, Latino/Latina/Latinx, Native American/Indigenous, Polynesian or Pacific Islander, White
    const Asian = 'Asian';
    const Black = 'Black/African American';
    const Latin = 'Latino/Latina/Latinx';
    const Native = 'Native American/Indigenous';
    const Polynesian = 'Polynesian or Pacific Islander';
    const White = 'White';
}
