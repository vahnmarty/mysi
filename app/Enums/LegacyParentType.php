<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LegacyParentType extends Enum
{
    use EnumCustomTrait;
    
    const Father = 'Father';
    const Mother = 'Mother';
    const Grandfather = 'Grandfather';
    const Uncle = 'Uncle';
    const Aunt = 'Aunt';
    const GreatGrandfather = 'Great Grandfather';
    const Stepfather = 'Stepfather';
    const Stepmother = 'Stepmother';
    const Cousin = 'Cousin';
}
