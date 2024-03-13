<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Application;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\NotificationLetter;
use App\Enums\NotificationStatusType;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\NotificationLetterResource\Pages;
use App\Filament\Resources\NotificationLetterResource\RelationManagers;

class NotificationLetterResource extends Resource
{
    protected static ?string $model = NotificationLetter::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('reference')
                    ->options(NotificationStatusType::asSelectArray()),
                Forms\Components\TextInput::make('title')->required(),
                TiptapEditor::make('content')
                    ->required()
                    ->extraInputAttributes(['style' => 'min-height:500px'])
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('preview')
                    ->form([
                        Forms\Components\Select::make('application_id')
                            ->label('Application')
                            ->options(Application::optionList())
                    ])
                    ->action(function(NotificationLetter $record, array $data){
                        return redirect()->to('notification-sample/' . $record->id . '?application_id=' . $data['application_id']);
                    })
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
            'index' => Pages\ListNotificationLetters::route('/'),
            'create' => Pages\CreateNotificationLetter::route('/create'),
            'edit' => Pages\EditNotificationLetter::route('/{record}/edit'),
            'variables' => Pages\NotificationVariables::route('/variables')
        ];
    }    
}
