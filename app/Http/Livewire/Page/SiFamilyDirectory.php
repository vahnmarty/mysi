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

    public $account_id, $account_family;

    public $last_updated_at;

    public $directory = [];
    
    public function render()
    {
        return view('livewire.page.si-family-directory');
    }

    public function mount()
    {
        $this->form->fill();

        $this->directory = FamilyDirectory::get();

        $this->last_updated_at = FamilyDirectory::first()?->updated_at->format('F d, Y H:i a');
    }

    public function getTableQuery()
    {
        return FamilyDirectory::where('account_id', $this->account_id);
    }

    public function isTableSearchable(): bool
    {
        return false;
    }

    protected function getTableColumns(): array 
    {
        return [
            
            TextColumn::make('contact_type')
                ->label('Type'),
            TextColumn::make('full_name')
                ->label('Name')
                ->wrap(),
            TextColumn::make('graduation_year')
                ->label('Class Of'),
            TextColumn::make('personal_email')
                ->label('Email'),
            TextColumn::make('mobile_phone')
                ->label('Phone')
                ->formatStateUsing(fn($state) => format_phone($state)),
            TextColumn::make('home_address')
                ->label('Address'),
        ];
    }
    protected function getDefaultTableSortColumn(): ?string
    {
        return 'full_name';
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


    public function open($id, $accountId)
    {
        $this->account_id = $accountId;

        $account = Account::find($accountId);

        $this->account_family = $account->account_name;

        $this->dispatchBrowserEvent('open-modal', 'show-details');
    }

    public function isTablePaginationEnabled(): bool 
    {
        return false;
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
