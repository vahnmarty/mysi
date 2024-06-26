<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ShirtSize extends Enum
{
    use EnumCustomTrait;
    
    const Small = 'Small';
    const Medium = 'Medium';
    const Large = 'Large';
    const XLarge = 'X-Large';
    const XXLarge = 'XX-Large';
}
