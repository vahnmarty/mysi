<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AccountAction extends Enum
{
    const Login = 0;
    const CreateAccount = 1;
    const NoAccount = 2;
}
