<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChildResource\Pages;
use App\Filament\Resources\ChildResource\RelationManagers;
use App\Models\Child;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChildResource extends Resource
{
    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $model = Child::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $pluralLabel = 'Students';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('application')
            ->select('children.*')
            ->join('users', 'children.account_id', '=', 'users.account_id')
            ->where('current_grade', 8);
            //->withTrashed();
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
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_school')
                    ->label('Current School')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_grade')
                    ->label('Current Grade')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('personal_email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mobile_phone')
                    ->label('Phone')
                    ->formatStateUsing(fn($state) => format_phone($state))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_application')
                    ->query(fn (Builder $query): Builder => $query->has('application', true))
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn(Child $record) => url('admin/applications', $record->application?->id))
                    ->hidden(fn(Child $record) => empty($record->application))
                    ->color('primary')
                    ->label('View App'),
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
            'index' => Pages\ListChildren::route('/'),
            'create' => Pages\CreateChild::route('/create'),
            'edit' => Pages\EditChild::route('/{record}/edit'),
        ];
    }    
}
