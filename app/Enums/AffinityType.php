<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AffinityType extends Enum
{
    use EnumCustomTrait;
    
    const ArabAndMiddleEasternAffinity = 'Arab and Middle Eastern Affinity';
    const AsianStudentsCoalition = 'Asian Students’ Coalition';
    const AssociationOfLatinAmericanStudents = 'Association of Latin American Students';
    const BlackStudentUnion = 'Black Student Union';
    const JewishAffinityGroup = 'Jewish Affinity Group';
    const LGBTQPlusAffinity = 'LGBTQ+ Affinity';
    const PacificIslanderAssociation = 'Pacific Islander Association';
}
