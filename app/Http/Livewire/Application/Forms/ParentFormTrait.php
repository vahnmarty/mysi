<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Models\School;
use App\Enums\AddressType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;

trait ParentFormTrait{

    public function getParentForm()
    {
        return [
            
        ];
    }

}