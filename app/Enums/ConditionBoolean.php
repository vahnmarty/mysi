<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ConditionBoolean extends Enum
{
    const NotSpecified = 0;
    const Yes = 1;
    const No = 2;
}
