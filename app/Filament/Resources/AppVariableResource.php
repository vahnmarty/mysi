<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppVariableResource\Pages;
use App\Filament\Resources\AppVariableResource\RelationManagers;
use App\Models\AppVariable;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppVariableResource extends Resource
{
    protected static ?string $model = AppVariable::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('config')
                    ->disabled()
                    ->columnSpan('full'),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->columnSpan('full'),
                Forms\Components\TextInput::make('display_value')
                    ->label('Display')
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('config')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')->limit(50),
                Tables\Columns\TextColumn::make('display_value')
                    ->label('Display'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAppVariables::route('/'),
            //'create' => Pages\CreateAppVariable::route('/create'),
            //'edit' => Pages\EditAppVariable::route('/{record}/edit'),
        ];
    }    
}
