<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Enums\Traits\EnumCustomTrait;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SurveyReasonType extends Enum
{
    use EnumCustomTrait;
    
    const FINANCIAL_CONCERNS = 'Financial Concerns';
    const SCHOLARSHIP_AT_ANOTHER_SCHOOL = 'Scholarship at Another School';
    const SPECIAL_PROGRAM_OF_STUDY_AT_ANOTHER_SCHOOL = 'Special Program of Study at Another School';
    const DESIRE_A_SMALLER_SIZE_HIGH_SCHOOL = 'Desire a Smaller Size High School';
    const PREFER_A_SINGLE_SEX_HIGH_SCHOOL_EXPERIENCE = 'Prefer a Single Sex High School Experience';
    const COMMUTE_DISTANCE_FROM_HOME = 'Commute/Distance from Home';
    const LEGACY_AT_ANOTHER_SCHOOL = 'Legacy at Another School';
    const FRIENDS_AT_ANOTHER_SCHOOL = 'Friends at Another School';
    const RELIGIOUS_CONCERNS = 'Religious Concerns';
    const OTHER = 'Other';
}
