<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class EmploymentStatus extends Enum
{
    use EnumCustomTrait;

    const Employed = 'Employed';
    const NotEmployed = 'Not Employed';
    const Retired = 'Retired';
    const StayAtHomeParentGuardian = 'Stay-At-Home Parent/Guardian';
}
