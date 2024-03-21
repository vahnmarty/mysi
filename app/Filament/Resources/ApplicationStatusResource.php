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

    protected static ?string $navigationLabel = 'Applications (FA)';

    protected static ?string $pluralLabel = 'Applications (FA)';

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
                    ->label("FA")
                    ->sortable(),
                Tables\Columns\TextColumn::make("deposit_amount")
                    ->label("Deposit Amount"),
                Tables\Columns\TextColumn::make("annual_financial_aid_amount")
                    ->label("Annual FA Amount"),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('with_fa')
                    ->label('With FA')
                    ->nullable()
                    ->attribute('financial_aid')
                    ->queries(
                        true: fn (Builder $query) => $query->where('financial_aid', '!=', ''),
                        false: fn (Builder $query) => $query->whereNull('financial_aid'),
                        blank: fn (Builder $query) => $query,
                    ),
                Tables\Filters\SelectFilter::make('financial_aid')
                    ->label('Financial Aid')
                    ->options(array_combine(['A', 'B', 'C', 'D'], ['A', 'B', 'C', 'D']))
                    ->query(fn($data, Builder $query) => $query->where('financial_aid', 'LIKE', '%' . $data['value'] . '%' )),
                Tables\Filters\TernaryFilter::make('fa_acknowledged_at')
                    ->label('Letters Read')
                    ->nullable()
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('fa_acknowledged_at'),
                        false: fn (Builder $query) => $query->whereNull('fa_acknowledged_at')->orWhere('fa_acknowledged_at', ''),
                        blank: fn (Builder $query) => $query,
                    ),
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
