<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Laravel\Passport\Client;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OauthClientResource\Pages;
use App\Filament\Resources\OauthClientResource\RelationManagers;

class OauthClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationLabel = 'OAuth Clients';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('redirect')
                    ->label('Callback URL')
                    ->placeholder('e.g. https://test.salesforce.com/services/oauth2/authorize'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('secret')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOauthClients::route('/'),
        ];
    }    
}
