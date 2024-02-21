<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PartnerType extends Enum
{
    use EnumCustomTrait;
    
    //const Self = 'Self';
    const HusbandWife = 'Husband/Wife';
    const Partner = 'Partner';
    const Ex = 'Ex-Husband/Ex-Wife';
    const Parent = 'Parent';
    const Other = 'Other';
    const NoRelationship = 'No Relationship';
}
