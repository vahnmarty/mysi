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
            Placeholder::make('si_directory_description')
                ->label('')
                ->content(new HtmlString('<p>* This section is to be completed by a parent/guardian.</p>
                    <p class="mt-4">
                    SI maintains a directory of current students and their parents/guardians.  This directory can be used to foster collaboration among families for carpools, volunteering, school activities and other general community support.  Please let us know what information you would like to share with the rest of our community.  This information will be available around August.
                    </p>
                ')),
            TableRepeater::make('student_directory')
                ->label('')
                ->disableItemCreation()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->hideLabels()
                ->extraAttributes(['id' => 'table-si-directory'])
                ->columnSpan('full')
                ->schema([
                    Hidden::make('id')->reactive(),
                    Hidden::make('first_name')->reactive(),
                    Hidden::make('last_name')->reactive(),
                    TextInput::make('full_name')
                        ->label('Student')
                        ->afterStateHydrated(function(Closure $get, Closure $set){
                            $set('full_name', $get('first_name') . ' ' . $get('last_name'));
                        })
                        ->reactive()
                        ->disabled()
                        ->required(),
                    Select::make('share_personal_email')
                        ->label('Share Personal Email?')
                        ->disableLabel()
                        ->required()
                        ->options([
                            1 => 'Yes',
                            0 => 'No'
                        ])
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveStudent('share_personal_email', $state);
                        }),
                    Select::make('share_mobile_phone')
                        ->label('Share Mobile Phone?')
                        ->disableLabel()
                        ->required()
                        ->options([
                            1 => 'Yes',
                            0 => 'No'
                        ])
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveStudent('share_mobile_phone', $state);
                        }),
                    Select::make('share_full_address')
                        ->label('Share Full Address?')
                        ->disableLabel()
                        ->required()
                        ->options([
                            1 => 'Yes',
                            0 => 'No'
                        ])
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveStudent('share_full_address', $state);
                        }),
                    ]),
            TableRepeater::make('parents_directory')
                ->label('')
                ->disableItemCreation()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->hideLabels()
                ->extraAttributes(['id' => 'table-si-directory'])
                ->columnSpan('full')
                ->schema([
                    Hidden::make('id')->reactive(),
                    Hidden::make('first_name')->reactive(),
                    Hidden::make('last_name')->reactive(),
                    TextInput::make('full_name')
                        ->label('Parent/Guardian')
                        ->afterStateHydrated(function(Closure $get, Closure $set){
                            $set('full_name', $get('first_name') . ' ' . $get('last_name'));
                        })
                        ->reactive()
                        ->disabled()
                        ->required(),
                    Select::make('share_personal_email')
                        ->label('Share Personal Email?')
                        ->disableLabel()
                        ->required()
                        ->options([
                            1 => 'Yes',
                            0 => 'No'
                        ])
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'), 'share_personal_email', $state);
                        }),
                    Select::make('share_mobile_phone')
                        ->label('Share Mobile Phone?')
                        ->disableLabel()
                        ->required()
                        ->options([
                            1 => 'Yes',
                            0 => 'No'
                        ])
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'), 'share_mobile_phone', $state);
                        }),
                    Select::make('share_full_address')
                        ->label('Share Full Address?')
                        ->disableLabel()
                        ->required()
                        ->options([
                            1 => 'Yes',
                            0 => 'No'
                        ])
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'), 'share_full_address', $state);
                        }),
                ])
        ];
    }

    
}