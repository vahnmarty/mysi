<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LanguageFacts extends Enum
{
    use EnumCustomTrait;
    
    const level4 = "My current school is a language immersion program";
    const level5 = "I am currently taking or have taken this language at my current school";
    const level6 = "I am currently taking a course in this language outside of school";
    const level0 = "None of the above";
}
