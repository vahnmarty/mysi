<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class GradeLevel extends Enum
{
    // Dropdown options are:  Pre-kindergarten, Kindergarten, 1, 2, 3, 4, 5, 6, 7, 8, Freshman, Sophomore, Junior, Senior, College, Post HS/College
    const PreKindergarten = 'Pre-kindergarten';
    const Kindergarten = 'Kindergarten';
    const Grade1 = 'Grade 1';
    const Grade2 = 'Grade 2';
    const Grade3 = 'Grade 3';
    const Grade4 = 'Grade 4';
    const Grade5 = 'Grade 5';
    const Grade6 = 'Grade 6';
    const Grade7 = 'Grade 7';
    const Grade8 = 'Grade 8';
    const Freshman = 'Freshman';
    const Sophomore = 'Sophomore';
    const Junior = 'Junior';
    const Senior = 'Senior';
    const College = 'College';
    const PostCollege = 'Post HS/College';

}
