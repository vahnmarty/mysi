<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ChangePassword extends Page
{
    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static string $view = 'filament.pages.change-password';
}
