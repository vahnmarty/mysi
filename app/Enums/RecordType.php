<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RecordType extends Enum
{
    const Student   = '0128a000000ii6qAAA';
    const Prospect  = '0128a000000ii6pAAA';
    const Parent    = '0128a000000ii6oAAA';

    // 0128a000000ii6qAAA for Student, if new record; 0128a000000ii6pAAA for Prospect; 0128a000000ii6oAAA for Parent, if new; use existing RecordTypeId if old record
}
