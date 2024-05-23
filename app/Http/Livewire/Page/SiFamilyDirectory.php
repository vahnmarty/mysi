<?php

namespace App\Http\Livewire\Page;

use App\Models\Child;
use App\Models\Address;
use Livewire\Component;
use App\Models\CoursePlacement;
use App\Models\FamilyDirectory;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

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
            TextColumn::make('name')
                ->searchable(isIndividual: true)
                ->sortable()
                ->wrap(),
            BadgeColumn::make('type')
                ->colors([
                    'success' => 'STUDENT',
                    'warning' => 'GUARDIAN',
                ])
                ->sortable(),
            TextColumn::make('email')
                ->wrap()
                ->searchable(isIndividual: true),
            TextColumn::make('phone')
                ->formatStateUsing(fn($state) => format_phone($state)),
            TextColumn::make('address')
                ->wrap(),
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
            //Action::make('see_scores')
                //->label('See Scores')
        ];
    }

    public function isTablePaginationEnabled(): bool 
    {
        return false;
    }

    public function getTableEmptyStateIcon(): ?string 
    {
        return 'heroicon-o-collection';
    }
 
    public function getTableEmptyStateHeading(): ?string
    {
        return 'No Available Course Placements';
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
