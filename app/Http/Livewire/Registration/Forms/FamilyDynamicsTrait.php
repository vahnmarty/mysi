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
use App\Models\FamilyDynamic;
use App\Enums\AddressLocation;
use App\Enums\LivingSituationType;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use App\Models\GuardianRelationship;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Models\Parents as ParentModel;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;


trait FamilyDynamicsTrait{
    
    public function getFamilyMatrix()
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
                    Hidden::make('id')->reactive(),
                    Hidden::make('first_name')->reactive(),
                    Hidden::make('last_name')->reactive(),
                    TextInput::make('full_name')
                        ->label('Parent/Guardian')
                        ->afterStateHydrated(function(Closure $get, Closure $set){
                            $set('full_name', $get('first_name') . ' ' . $get('last_name'));
                        })
                        ->reactive()
                        ->required()
                        ->disabled(),
                    Select::make('relationship_type')
                        ->label('Relationship to Applicant')
                        ->disableLabel()
                        ->options(ParentType::asSameArray())
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'), 'relationship_type', $state);
                        }),
                    Select::make('address_location')
                        ->label('Address Location')
                        ->disableLabel()
                        ->options(function(){
                            return Address::where('account_id', accountId())->pluck('address_type', 'address_type')->toArray();
                        })
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'), 'address_location', $state);
                        })
                        ->required(),
                    Select::make('living_situation')
                        ->label('Living Situation')
                        ->disableLabel()
                        ->options(LivingSituationType::asSameArray())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'), 'living_situation', $state);
                        }),
                    Checkbox::make('deceased_flag')
                        ->label('Deceased')
                        ->reactive()
                        ->extraAttributes(['class' => 'hide-checkbox-label flex-1'])
                        ->afterStateUpdated(function(Closure $get, Closure $set, $state){
                            $this->autoSaveParent($get('id'), 'deceased_flag', $state);
                            if($get('deceased_flag')){
                                $set('is_primary', false);
                            }
                        }),
                    // Toggle::make('is_primary')
                    //     ->label('Primary?')
                    //     ->reactive()
                    //     ->afterStateUpdated(function(Closure $get, Closure $set, Toggle $component, $state){
                    //         $this->autoSaveParent($get('id'), 'is_primary', $state);

                    //         if($get('is_primary')){
                    //             $set('deceased_flag', false);
                                
                    //             foreach($this->data['parents_matrix'] as $uuid => $item)
                    //             {
                    //                 if($item['id'] != $get('id')){
                    //                     $this->data['parents_matrix'][$uuid]['is_primary'] = false;
                    //                 }
                    //             }
                    //         }
                    //     }),
                ]),
            TableRepeater::make('siblings_matrix')
                ->label('')
                ->disableItemCreation()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->hideLabels()
                ->columnSpan('full')
                ->extraAttributes(['id' => 'table-sibling-matrix'])
                ->schema([
                    Hidden::make('id')->reactive(),
                    Hidden::make('first_name')->reactive(),
                    Hidden::make('last_name')->reactive(),
                    TextInput::make('full_name')
                        ->label('Sibling')
                        ->afterStateHydrated(function(Closure $get, Closure $set){
                            $set('full_name', $get('first_name') . ' ' . $get('last_name'));
                        })
                        ->reactive()
                        ->required()
                        ->disabled()
                        ->disableLabel(),
                    Select::make('relationship_type')
                        ->label('Relationship to Applicant')
                        ->disableLabel()
                        ->options(SiblingType::asSameArray())
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'relationship_type', $state);
                        }),
                    Select::make('address_location')
                        ->label('Address Location')
                        ->disableLabel()
                        ->options(function(){
                            return Address::where('account_id', accountId())->pluck('address_type', 'address_type')->toArray();
                        })
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'address_location', $state);
                        })
                        ->required(),
                    Select::make('living_situation')
                        ->label('Living Situation')
                        ->disableLabel()
                        ->options(LivingSituationType::asSameArray())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'living_situation', $state);
                        }),
                    // Placeholder::make('blank_deceased')
                    //     ->label(new HtmlString('<span class="invisible">*Deceased</span>'))
                    //     ->content('')
                    //     ->disabled()
                    // Placeholder::make('status')
                    //     ->label('')
                    //     ->content(new HtmlString('<div class="w-24"></div>')),
                    // Placeholder::make('status2')
                    //     ->label('')
                    //     ->content(new HtmlString('<div class="w-24"></div>')),
                ]),
                Repeater::make('family_dynamics')
                    ->label('')
                    ->disableItemCreation()
                    ->disableItemDeletion()
                    ->disableItemMovement()
                    ->columnSpan('full')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Hidden::make('id'),
                                TextInput::make('full_name')->label('')->disabled(),
                                Hidden::make('partner_full_name'),
                                Select::make('relationship')
                                    ->columnSpan(2)
                                    ->options(PartnerType::asSameArray())
                                    ->label(fn(Closure $get) => 'Relationship to ' . $get('partner_full_name') )
                                    ->inlineLabel()
                                    ->lazy()
                                    ->required()
                                    ->afterStateUpdated(function(Closure $get, $state){
                                        $this->autoSaveFamily($get('id'), 'relationship', $state);
                                    }),
                            ])
                    ])
        ];
    }

    private function autoSaveMatrix($id, $column, $value)
    {
        $model = FamilyMatrix::find($id);
        $model->$column = $value;
        $model->save();
    }

    public function getParentsMatrix()
    {
        $account = Account::find(accountId());

        return $account->parents->toArray();
    }

    public function getRelationshipMatrix()
    {
        $array = [];
        
        foreach($this->getParentsMatrix() as $parent){

            $parentModel = ParentModel::find($parent['id']);
            
            foreach($this->getParentsMatrix() as $y => $partner){

                if($parent['id'] != $partner['id'])
                {

                    $relationship = $parentModel->guardianRelationships()->firstOrCreate([
                        'account_id' => accountId(),
                        'partner_id' => $partner['id'],
                    ]);

    
                    $array[] = $relationship;
                }
                
            }

        }

        return $array;
    }


    public function autoSaveFamily($id, $column, $value)
    {
        $model = GuardianRelationship::find($id);
        $model->$column = $value;
        $model->save();
    }
}