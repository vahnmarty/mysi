<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LivingSituationType extends Enum
{
    use EnumCustomTrait;
    
    const LivesFullTime = 'Lives With Student Full Time';
    const LivesPartTime = 'Lives With Student Part Time';
    const DoesNotLive = 'Does Not Live With Student';
}
