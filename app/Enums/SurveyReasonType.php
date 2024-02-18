<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SurveyReasonType extends Enum
{
    use EnumCustomTrait;
    
    const Reason1 = 'Reason 1';
    const Reason2 = 'Reason 2';
    const Reason3 = 'Reason 3';
}
