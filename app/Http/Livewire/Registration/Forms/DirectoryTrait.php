<?php

namespace App\Http\Livewire\Registration\Forms;

use Closure;
use App\Models\Child;
use App\Models\Account;
use App\Models\Address;
use App\Enums\ParentType;
use App\Enums\PartnerType;
use App\Enums\SiblingType;
use App\Models\FamilyMatrix;
use App\Enums\AddressLocation;
use App\Enums\LivingSituationType;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;


trait DirectoryTrait{
    
    public function getSIDirectoryForm()
    {
        return [
            Placeholder::make('matrix_form_description')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by a parent/guardian. ')),
                
            TableRepeater::make('parents_matrix')
                ->label('')
                ->disableItemCreation()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->hideLabels()
                ->extraAttributes(['id' => 'table-parent-matrix'])
                ->columnSpan('full')
                ->schema([

                ])
        ];
    }

    
}