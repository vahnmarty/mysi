<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers;
use App\Models\Account;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountResource extends Resource
{
    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('account_name'),
                Forms\Components\TextInput::make('phone'),
                Forms\Components\TextInput::make('sf_account_id'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->rowIndex(),
                Tables\Columns\TextColumn::make('account_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('users_count')
                    ->label("Users")
                    ->counts('users'),
                Tables\Columns\TextColumn::make('users.email')->sortable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('sf_account_id')->label('sf_account_id'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_users')
                ->query(fn (Builder $query): Builder => $query->has('users')),
                Tables\Filters\Filter::make('multiple_users')
                ->query(fn (Builder $query): Builder => $query->has('users', '>', 1)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ParentsRelationManager::class,
            RelationManagers\UsersRelationManager::class,
            RelationManagers\AddressesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'view' => Pages\ViewAccount::route('/{record}'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
