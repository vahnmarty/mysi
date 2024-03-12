<?php

namespace App\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\CheckboxList;

class CustomCheckboxList extends CheckboxList
{
    public $disabledOptions = [];

    public $disableOptionsWhen = null;

    protected string $view = 'forms.components.custom-checkbox-list';


    public function disableOptionWhen($option): static
    {
        $this->disableOptionsWhen = $option;

        return $this;
    }
}
