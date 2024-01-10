<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class EmergencyContactRelationshipType extends Enum
{
    use EnumCustomTrait;
    
    const AuntUncle = 'Aunt/Uncle';
    const BrotherSister = 'Brother/Sister';
    const Grandparent = 'Grandparent';
    const Neighbor = 'Neighbor';
    const Friend = 'Friend';
    const Other = 'Other';
}
