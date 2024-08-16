<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Salutation extends Enum
{
    use EnumCustomTrait;
    
    // Dropdown options are:  Mr., Mrs., Ms., Dr., Prof., Rev., Hon.
    const Mr = 'Mr.';
    const Mrs = 'Mrs.';
    const Ms = 'Ms.';
    const Dr = 'Dr.';
    const Prof = 'Prof.';
    const Rev = 'Rev.';
    const Hon = 'Hon.';
    const Capt = 'Capt.';

}
