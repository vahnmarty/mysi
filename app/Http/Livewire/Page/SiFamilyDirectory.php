<?php

namespace App\Http\Livewire\Page;

use DB;
use App\Models\Child;
use App\Models\Account;
use App\Models\Address;
use Livewire\Component;
use App\Models\CoursePlacement;
use App\Models\FamilyDirectory;
use App\Forms\Components\TextOnly;
use Filament\Tables\Actions\Action;
use App\Models\Views\SiDirectoryView;
use Filament\Forms\Components\Hidden;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use App\Forms\Components\ReadonlyTextbox;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;

class SiFamilyDirectory extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $data = [];
    
    public function render()
    {
        return view('livewire.page.si-family-directory');
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function getTableQuery()
    {
        return FamilyDirectory::query();
    }

    public function isTableSearchable(): bool
    {
        return false;
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('full_name')
                ->label('Name')
                ->searchable(isIndividual: true)
                ->sortable()
                ->wrap(),
            BadgeColumn::make('contact_type')
                ->label('Type')
                ->colors([
                    'success' => 'Student',
                    'warning' => 'Guardian',
                ])
                ->sortable(),
            TextColumn::make('grad_year')
                ->label('Graduation Year')
                ->sortable(),
        ];
    }

    public function getAddress($type, $full = true)
    {
        $address = Address::where('address_type', $type)->where('account_id', accountId())->first();

        if($address){
            if($full){
                return $address->getFullAddress();
            }

            return $address->getShortAddress();
        }

        return '---';
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('view_family')
                ->mountUsing(function(ComponentContainer $form, $record){

                    $tree = FamilyDirectory::where('account_id', $record->account_id)->get()->toArray();

                    return $form->fill([
                        'tree' => $tree
                    ]);
                })
                ->form([
                    TableRepeater::make('tree')
                        ->extraAttributes([
                            'class' => 'table-flat'
                        ])
                        ->label('')
                        ->disableItemCreation()
                        ->disableItemDeletion()
                        ->disableItemMovement()
                        ->hideLabels()
                        ->columnSpan('full')
                        ->schema([
                            TextOnly::make('full_name')
                                ->label('Name')
                                ->disabled(),
                            TextOnly::make('contact_type')
                                ->label('Type')
                                ->disabled(),
                            TextOnly::make('grad_year')
                                ->label('Graduation Year')
                                ->disabled(),
                            TextOnly::make('personal_email')
                                ->label('Email')
                                ->disabled(),
                            TextOnly::make('mobile_phone')
                                ->label('Mobile')
                                ->disabled(),
                            TextOnly::make('home_address')
                                ->label('Address')
                                ->disabled(),
                        ])

                ])
                ->action('hello')
                ->label('Family Contact Information')
                ->modalActions([])
        ];
    }

    public function hello()
    {

    }

    public function isTablePaginationEnabled(): bool 
    {
        return true;
    }

    protected function getTableRecordsPerPageSelectOptions(): array // [tl! focus:start]
    {
        return [100, 200, 500, 1000];
    }

    public function getTableEmptyStateIcon(): ?string 
    {
        return 'heroicon-o-collection';
    }
 
    public function getTableEmptyStateHeading(): ?string
    {
        return 'SI Family Directory is not available at the moment.';
    }

    public function getTableEmptyStateDescription(): ?string
    {
        return 'No records available';
    }

    protected function getTableActionsColumnLabel(): ?string
    {
        return 'Action';
    }

    protected function getFormStatePath(): string 
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
            //Hidden::make('share_personal_email')
        ];
    }
}
