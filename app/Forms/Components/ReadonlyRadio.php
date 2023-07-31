<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Radio;

class ReadonlyRadio extends Radio
{
    protected string $view = 'forms.components.readonly-radio';
}
