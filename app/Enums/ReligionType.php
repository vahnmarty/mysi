<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ReligionType extends Enum
{
    use EnumCustomTrait;
    
    const Catholic = 'Catholic';
    const Christian = 'Christian';
    const Buddhist = 'Buddhist';
    const Hindu = 'Hindu';
    const Jewish = 'Jewish';
    const Muslim = 'Muslim';
    const NoReligion = 'No Religion';
    const Spiritual = 'Spiritual';
    const Other = 'Other';
}
