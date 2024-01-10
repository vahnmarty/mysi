<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ArtProgramsType extends Enum
{
    use EnumCustomTrait;
    
    const Choir = 'Choir';
    const BandAndOrchestra = 'Band and Orchestra';
    const JazzBand = 'Jazz Band';
    const Dance = 'Dance';
    const Theater = 'Theater (Fall/Play/Spring/Musical)';
    const PepBand = 'PEP Band';
}
