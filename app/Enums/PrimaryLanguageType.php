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

    const Arabic = 'Arabic';
    const Cantonese = 'Cantonese';
    const French = 'French';
    const Korean = 'Korean';
    const Mandarin = 'Mandarin';
    const Portuguese = 'Portuguese';
    const Russian = 'Russian';
    const Spanish = 'Spanish';
    const Tagalog = 'Tagalog';
    const Vietnamese = 'Vietnamese';


    const Other = 'Other';
}
