<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ParentSuffix extends Enum
{
    use EnumCustomTrait;
    

    const Sr = 'Sr.';
    const Jr = 'Jr.';
    const II = 'II';
    const III = 'III';
    const IV = 'IV';
    const Esq = 'Esq.';
    const MD = 'MD';
    const DDS = 'DDS';
}
