<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Departments extends Enum
{
    use EnumCustomTrait;

    const Admissions = 'Admissions';
    //const Academics = 'Center for Academics and Targeted Support';
    const TechSupport = 'Tech Support';
}
