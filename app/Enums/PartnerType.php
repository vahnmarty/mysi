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
    const Husband = 'Husband';
    const Wife = 'Wife';
    const Partner = 'Partner';
    const ExHusband = 'Ex-husband';
    const ExWife = 'Ex-wife';
    const ExPartner = 'Ex-partner';
    const Parent = 'Parent';
    const Child = 'Child';
    const Sibling = 'Sibling';
    const InLaws = 'In-laws';
    const Other = 'Other';
    const NoRelationship = 'No relationship';

    
}
