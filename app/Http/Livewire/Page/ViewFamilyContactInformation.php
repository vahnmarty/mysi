<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use App\Models\FamilyDirectory;
use App\Models\Views\SiDirectoryView;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;

class ViewFamilyContactInformation extends Component implements HasTable
{
    use InteractsWithTable;

    public $account_id;

    protected $listeners = ['set-account' => 'setAccountId'];

    public function mount($accountId)
    {
        $this->account_id = $accountId;
    }
    
    public function render()
    {
        return view('livewire.page.view-family-contact-information');
    }

    public function getTableQuery()
    {
        return FamilyDirectory::where('account_id', $this->account_id);
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('full_name'),
            BadgeColumn::make('type')
                ->colors([
                    'success' => 'STUDENT',
                    'warning' => 'GUARDIAN',
                ])
                ->sortable(),
            TextColumn::make('graduation_year')
                ->label('Graduation Year')
                ->sortable(),
        ];
    }

    public function open()
    {
        $this->dispatchBrowserEvent('open-modal', 'show-details');
    }
}
