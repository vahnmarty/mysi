<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PrimaryLanguageType extends Enum
{
    use EnumCustomTrait;
    
    const English = 'English';
    const Spanish = 'Spanish';
    const Mandarin = 'Mandarin';
    const Cantonese = 'Cantonese';
    const Tagalog = 'Tagalog';
    const Vietnamese = 'Vietnamese';
    const Arabic = 'Arabic';
    const French = 'French';
    const Korean = 'Korean';
    const Russian = 'Russian';
    const Portuguese = 'Portuguese';
    const Other = 'Other';
}
