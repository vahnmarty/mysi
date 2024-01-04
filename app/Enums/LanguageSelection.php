<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LanguageSelection extends Enum
{
    use EnumCustomTrait;
    
    const French = 'French';
    const Latin = 'Latin';
    const Mandarin = 'Mandarin';
    const Spanish = 'Spanish';
}
