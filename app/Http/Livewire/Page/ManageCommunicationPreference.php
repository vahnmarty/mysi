<?php

namespace App\Http\Livewire\Page;

use Closure;
use App\Models\Parents;
use Livewire\Component;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;

class ManageCommunicationPreference extends Component implements HasForms
{
    use InteractsWithForms;
    
    public $data = [];

    public function render()
    {
        return view('livewire.page.manage-communication-preference');
    }

    public function mount()
    {
        $parents = Parents::where('account_id', accountId())->get()->toArray();
        $this->form->fill(['parents' => $parents]);
    }

    protected function getFormStatePath(): string 
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
            TableRepeater::make('parents')
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
                        ]),
                    Select::make('share_mobile_phone')
                        ->label('Share Mobile Phone?')
                        ->disableLabel()
                        ->required()
                        ->options([
                            1 => 'Yes',
                            0 => 'No'
                        ]),
                    Select::make('share_full_address')
                        ->label('Share Full Address?')
                        ->disableLabel()
                        ->required()
                        ->options([
                            1 => 'Yes',
                            0 => 'No'
                        ])
                ])
        ];
    }

    public function update()
    {
        $data = $this->form->getState();

        foreach($data['parents'] as $item)
        {
            $parent = Parents::find($item['id']);
            $parent->share_personal_email = $item['share_personal_email'];
            $parent->share_mobile_phone = $item['share_mobile_phone'];
            $parent->share_full_address = $item['share_full_address'];
            $parent->save();
        }

        Notification::make()
            ->title('Updated Successfully')
            ->success()
            ->send();
    }
}
