<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationStatusResource\Pages;
use App\Filament\Resources\ApplicationStatusResource\RelationManagers;
use App\Models\ApplicationStatus;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApplicationStatusResource extends Resource
{
    protected static ?string $model = ApplicationStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Applications';

    protected static ?string $pluralLabel = 'Applications';

    protected static function shouldRegisterNavigation(): bool
    {
        return !auth()->user()->isAdmin();
    }

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
                Tables\Columns\TextColumn::make("application.student.first_name")
                    ->label("Student First Name")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("application.student.last_name")
                    ->label("Student Last Name")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("application.student.current_school")
                    ->label("School"), // add not listed
                Tables\Columns\TextColumn::make("candidate_decision_status")
                    ->label("Student Decision"),
                Tables\Columns\TextColumn::make("financial_aid")
                    ->label("FA"),
                Tables\Columns\TextColumn::make("deposit_amount")
                    ->label("Deposit Amount"),
                Tables\Columns\TextColumn::make("annual_financial_aid_amount")
                    ->label("Annual FA Amount"),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('financial_aid')
                    ->label('Financial Aid')
                    ->options(array_combine(['A', 'B', 'B1', 'C', 'D'], ['A', 'B', 'B1', 'C', 'D'])),
                Tables\Filters\Filter::make('fa_acknowledged_at')
                    ->label('Letters Read')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('fa_acknowledged_at')),
                    Tables\Filters\SelectFilter::make('candidate_decision_status')
                    ->label('Student Decision')
                    ->options([
                        'Accepted' => 'Accepted',
                        'Declined' => 'Declined'
                    ])
            ])
            ->actions([
            ])
            ->bulkActions([
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
            'index' => Pages\ListApplicationStatuses::route('/'),
        ];
    }    
}
