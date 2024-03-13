<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrepShopResource\Pages;
use App\Filament\Resources\PrepShopResource\RelationManagers;
use App\Models\PrepShop;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrepShopResource extends Resource
{
    protected static ?string $model = PrepShop::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'SI Prep Shop';

    protected static ?string $pluralLabel = 'SI Prep Shop';

    protected static ?string $navigationGroup = 'Settings';

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')->required(),
                Forms\Components\TextInput::make('morning_schedule'),
                Forms\Components\TextInput::make('afternoon_schedule'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')->dateTime('F d, Y'),
                Tables\Columns\TextColumn::make('morning_schedule'),
                Tables\Columns\TextColumn::make('afternoon_schedule'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPrepShops::route('/'),
            // 'create' => Pages\CreatePrepShop::route('/create'),
            // 'edit' => Pages\EditPrepShop::route('/{record}/edit'),
        ];
    }    
}
