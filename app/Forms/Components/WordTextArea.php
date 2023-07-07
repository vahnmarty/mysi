<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea;

class WordTextArea extends Textarea
{
    protected string $view = 'forms.components.word-text-area';

    protected $wordLimit = 75;

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
