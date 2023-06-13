<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Component;

class CustomRepeater extends Repeater
{
    protected string $view = 'forms.components.custom-repeater';

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->registerListeners([
            'repeater::deleteItem' => [
                function (Repeater $component, string $statePath, string $uuidToDelete): void {
                    if ($statePath !== $component->getStatePath()) {
                        return;
                    }

                    $items = $component->getState();

                    dd('here', $items[$uuidToDelete]);

                    unset($items[$uuidToDelete]);

                    $livewire = $component->getLivewire();
                    data_set($livewire, $statePath, $items);
                },
            ],
        ]);
    }
}
