<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;
use Wiebenieuwenhuis\FilamentCharCounter\TextInput;

class WordTextInput extends TextInput
{
    protected string $view = 'forms.components.word-text-input';

    protected $wordLimit = 30;

    public function wordLimit(int $value): self
    {
        $this->wordLimit = $value;
        return $this;
    }

    public function getWordLimit(): int
    {
        return $this->wordLimit;
    }
    
}
