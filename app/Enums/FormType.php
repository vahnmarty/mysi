<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class FormType extends Enum
{
    const Date = 'date';
    const DateTime = 'datetime';
    const RangeYear = 'range_year';
}
