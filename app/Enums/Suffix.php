<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Suffix extends Enum
{
    use EnumCustomTrait;
    
    const Jr = 'Jr.';
    const II = 'II';
    const III = 'III';
    const IV = 'IV';
    const V = 'V';
}
