<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ParentType extends Enum
{
    const Father = 'Father';
    const Mother = 'Mother';
    const Grandmother = 'Grandmother';
    const Grandfather = 'Grandfather';
    const Guardian = 'Guardian';
}
