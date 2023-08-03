<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Component;
use Str;

class ParentRepeater extends Repeater
{
    protected string $view = 'forms.components.parent-repeater';

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerListeners([
            'parentRepeater::deleteItem' => [
                function (ParentRepeater $component, string $statePath, string $uuidToDelete): void {
                    if ($statePath !== $component->getStatePath()) {
                        return;
                    }

                    $items = $component->getState();

                    unset($items[$uuidToDelete]);

                    $livewire = $component->getLivewire();
                    data_set($livewire, $statePath, $items);
                },
            ],
        ]);
    }
}
