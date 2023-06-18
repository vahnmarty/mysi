<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class FamilySpiritualityType extends Enum
{
    use EnumCustomTrait;

    const ChurchBased = 'Church Based';
    const ServiceBased = 'Service Based';
    const ValuesEthics = 'Values/Ethics';
    const GlobalEnvironmental = 'Global/Environmental';
    const Other = 'Other';
}
