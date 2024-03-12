<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AcceptanceReasonType extends Enum
{
    use EnumCustomTrait;
    
    const Academics = 'Academics';
    const Athletics = 'Athletics';
    const PerformingArts = 'Performing Arts';
    const OtherCoCurricularsOffered = 'Other Co-curriculars Offered';
    const JesuitCatholicEducation = 'Jesuit/Catholic Education';
    const Diversity = 'Diversity';
    const LegacyConnection = 'Legacy Connection';
    const SocialJustice = 'Social Justice';
    const Community = 'Community';
    const Other = 'Other';
}
