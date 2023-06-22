<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\RelationManagers;
use App\Models\Application;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("student.first_name")
                    ->label("Student's Name")
                    ->formatStateUsing(fn(Application $record) => $record->student->getFullName())
                    ->searchable(),
                Tables\Columns\TextColumn::make("record_type"),
                Tables\Columns\TextColumn::make("appStatus.application_start_date")
                    ->label('Date Started')
                    ->dateTime(),
                Tables\Columns\TextColumn::make("appStatus.application_submit_date")
                    ->label('Date Submitted')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListApplications::route('/'),
            //'create' => Pages\CreateApplication::route('/create'),
            //'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }    
}
