<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LanguageCapability extends Enum
{
    use EnumCustomTrait;


    const level1 = "I speak this language every day";
    const level2 = "I understand this language but do not speak this language";
    const level3 = "I speak this language occasionally with family and/or friends";
    const level0 = "None of the above";
}
