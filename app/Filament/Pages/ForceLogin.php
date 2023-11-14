<?php

namespace App\Filament\Pages;

use Closure;
use App\Models\User;
use App\Models\Account;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class ForceLogin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-lock-open';

    protected static string $view = 'filament.pages.force-login';

    public $account, $user;

    protected function getFormSchema() : array
    {
        return [
            Select::make('account')
                ->options(Account::has('users')->get()->pluck('account_name', 'id'))
                ->preload()
                ->required()
                ->lazy(),
            Select::make('user')
                ->label('User Login')
                ->lazy()
                ->options(function(Closure $get){
                    if($get('account')){
                        return User::where('account_id', $get('account'))->get()->pluck('name', 'id');
                    }

                    return [];
                })
                ->preload()
                ->required()
        ];
    }

    public function save()
    {
        $data = $this->form->getState();

        return redirect()->route('impersonate', $data['user']);
    }

}
