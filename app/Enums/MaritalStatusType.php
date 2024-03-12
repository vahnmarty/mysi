<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class MaritalStatusType extends Enum
{
    use EnumCustomTrait;
    
    const Single = 'Single';
    const Married = 'Married';
    const Separated = 'Separated';
    const Divorced = 'Divorced';
    const Widowed = 'Widowed';
}
