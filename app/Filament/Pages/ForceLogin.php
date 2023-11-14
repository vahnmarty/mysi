<?php

namespace App\Filament\Pages;

use Closure;
use App\Models\User;
use App\Models\Account;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use CoringaWc\FilamentInputLoading\TextInput;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;

class ForceLogin extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationIcon = 'heroicon-o-lock-open';

    protected static string $view = 'filament.pages.force-login';

    protected static ?int $navigationSort = 3;

    public $account, $account_id, $user;

    protected function getFormSchema() : array
    {
        return [
            Select::make('account')
                ->options(Account::has('users')->get()->pluck('account_name', 'id'))
                ->preload()
                ->searchable()
                ->afterStateUpdated(function($state, Closure $set){
                    $set('account_id', $state);
                })
                ->lazy(),
            TextInput::make('account_id')
                ->lazy(),
            Select::make('user')
                ->label('User Login')
                ->helperText('You can search/select an account or you can input the account ID.')
                ->lazy()
                ->options(function(Closure $get, Closure $set){
                    if($get('account_id')){
                        $users =  User::where('account_id', $get('account_id'))->get()->pluck('name', 'id');

                        if(!empty($users[0])){
                            $set('user', $users[0]);
                        }
                        return $users;
                    }

                    return [];
                })
                ->preload()
                ->placeholder('Select User')
                ->required()
        ];
    }

    public function save()
    {
        $data = $this->form->getState();

        return redirect()->route('impersonate', $data['user']);
    }

}
