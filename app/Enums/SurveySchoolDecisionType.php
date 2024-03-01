<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SurveySchoolDecisionType extends Enum
{
    use EnumCustomTrait;
    
    const Accepted = 'Accepted';
    const WaitListed = 'Wait Listed';
    const NotAccepted = 'Not Accepted';
}
