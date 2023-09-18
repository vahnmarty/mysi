<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplementalRecommendationResource\Pages;
use App\Filament\Resources\SupplementalRecommendationResource\RelationManagers;
use App\Models\SupplementalRecommendation;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplementalRecommendationResource extends Resource
{
    protected static ?string $model = SupplementalRecommendation::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Recommendations';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('uuid'),
                Forms\Components\TextInput::make('requester_name'),
                Forms\Components\TextInput::make('requester_email'),
                Forms\Components\TextInput::make('recommender_name')
                    ->formatStateUsing(fn(SupplementalRecommendation $record) => $record->recommender_first_name . ' ' . $record->recommender_last_name),
                Forms\Components\Textarea::make('message'),
                Forms\Components\TextInput::make('relationship_to_student'),
                Forms\Components\TextInput::make('years_known_student'),
                Forms\Components\Textarea::make('recommendation'),
                Forms\Components\DatePicker::make('date_requested'),
                Forms\Components\DatePicker::make('date_received'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->rowIndex(),
                Tables\Columns\TextColumn::make('requester_name')->searchable(),
                Tables\Columns\TextColumn::make('recommender_email')->searchable(),
                Tables\Columns\TextColumn::make('date_requested')->dateTime('M d, Y'),
                Tables\Columns\TextColumn::make('date_received')->dateTime('M d, Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListSupplementalRecommendations::route('/'),
            //'create' => Pages\CreateSupplementalRecommendation::route('/create'),
            'edit' => Pages\EditSupplementalRecommendation::route('/{record}/edit'),
        ];
    }    
}
