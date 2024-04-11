<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Child;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CurrentStudentFinancialAid;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CurrentStudentFinancialAidResource\Pages;
use App\Filament\Resources\CurrentStudentFinancialAidResource\RelationManagers;

class CurrentStudentFinancialAidResource extends Resource
{
    protected static ?string $navigationGroup = 'Administration';
    
    protected static ?string $model = CurrentStudentFinancialAid::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function canViewAny(): bool
    {
        return self::shouldRegisterNavigation();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('child_id')
                    ->label('Student')
                    ->options(
                        Child::where('current_school', 'St. Ignatius College Preparatory')
                            ->get()
                            ->pluck('full_name', 'id'))
                    ->searchable()
                    ->required()
                    ->columnSpan('full')
                    ->lazy()
                    ->afterStateUpdated(function(Closure $set, $state){
                        $child = Child::find($state);
                        $set('account_id', $child->account_id);
                    }),
                Forms\Components\TextInput::make('account_id')
                    ->lazy()
                    ->extraInputAttributes(['readonly' => true]),
                Forms\Components\TextInput::make('financial_aid'),
                Forms\Components\TextInput::make('annual_financial_aid_amount')->numeric(),
                Forms\Components\TextInput::make('total_financial_aid_amount')->numeric(),
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("student.first_name")
                    ->label("Student First Name")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("student.last_name")
                    ->label("Student Last Name")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("financial_aid")
                    ->label("FA Type")
                    ->sortable(),
                Tables\Columns\TextColumn::make("annual_financial_aid_amount")
                    ->label("FA Annual")
                    ->money('usd'),
                Tables\Columns\TextColumn::make("total_financial_aid_amount")
                    ->label("FA Total")
                    ->money('usd'),
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
            'index' => Pages\ListCurrentStudentFinancialAids::route('/'),
            //'create' => Pages\CreateCurrentStudentFinancialAid::route('/create'),
            'edit' => Pages\EditCurrentStudentFinancialAid::route('/{record}/edit'),
        ];
    }    
}
