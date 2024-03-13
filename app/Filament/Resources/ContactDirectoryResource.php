<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactDirectoryResource\Pages;
use App\Filament\Resources\ContactDirectoryResource\RelationManagers;
use App\Models\ContactDirectory;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactDirectoryResource extends Resource
{
    protected static ?string $model = ContactDirectory::class;

    protected static ?string $navigationIcon = 'heroicon-o-speakerphone';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Contact Directory';

    protected static ?string $pluralLabel = 'Contact Directory';

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('url')->activeUrl(),
                Forms\Components\TextInput::make('representative_name'),
                Forms\Components\TextInput::make('representative_email'),
                Forms\Components\TextInput::make('representative_phone'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->wrap(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->wrap(),
                Tables\Columns\TextColumn::make('representative_name')
                    ->label('Person'),
                Tables\Columns\TextColumn::make('representative_email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('representative_phone')
                    ->label('Phone'),
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
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactDirectories::route('/'),
            //'create' => Pages\CreateContactDirectory::route('/create'),
            //'edit' => Pages\EditContactDirectory::route('/{record}/edit'),
        ];
    }    
}
