<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ParentType extends Enum
{
    use EnumCustomTrait;
    // Dropdown options are:  Father, Mother, Stepfather, Stepmother, Grandfather, Grandmother, Uncle, Aunt, Male Guardian, Female Guardian

    const Father = 'Father';
    const Mother = 'Mother';
    const Stepfather = 'Stepfather';
    const Stepmother = 'Stepmother';
    const Grandmother = 'Grandmother';
    const Grandfather = 'Grandfather';
    const Uncle = 'Uncle';
    const Aunt = 'Aunt';
    const MaleGuardian = 'Male Guardian';
    const FemaleGuardian = 'Female Guardian';
}
