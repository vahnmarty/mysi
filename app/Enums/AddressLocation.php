<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AddressLocation extends Enum
{
    use EnumCustomTrait;
    
    // Dropdown options are:  Primary Address, Secondary Address, Other Address 1 and Other Address 2
    const PrimaryAddress = 'Primary Address';
    const SecondaryAddress = 'Secondary Address';
    const OtherAddress1 = 'Other Address 1';
    const OtherAddress2 = 'Other Address 2';
}
