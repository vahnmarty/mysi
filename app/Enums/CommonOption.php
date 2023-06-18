<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CommonOption extends Enum
{
    const Yes = 1;
    const No = 2;
    const Unsure = 3;
}
