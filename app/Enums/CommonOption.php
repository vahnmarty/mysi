<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CommonOption extends Enum
{
    use EnumCustomTrait;
    
    const Yes = 'Yes';
    const No = 'No';
    const Unsure = 'Unsure';
}
