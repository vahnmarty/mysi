<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\FormType;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\NotificationSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\NotificationSettingResource\Pages;
use App\Filament\Resources\NotificationSettingResource\RelationManagers;

class NotificationSettingResource extends Resource
{
    protected static ?string $model = NotificationSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'School Timeline';


    public static function form(Form $form): Form
    {
        $years = range(2000, date('Y') + 10);

        return $form
            ->schema([
                Forms\Components\TextInput::make('config')
                    ->required()
                    ->disabled()
                    ->columnSpan('full'),
                Forms\Components\DatePicker::make('value')
                    ->required()
                    ->columnSpan('full')
                    ->visible(fn(NotificationSetting $record) => $record->form_type === FormType::Date),
                Forms\Components\DateTimePicker::make('value')
                    ->required()
                    ->columnSpan('full')
                    ->visible(fn(NotificationSetting $record) => $record->form_type === FormType::DateTime),
                Forms\Components\Select::make('value1')
                    ->required()
                    ->placeholder('YYYY')
                    ->options(array_combine($years, $years))
                    ->visible(fn(NotificationSetting $record) => $record->form_type === FormType::RangeYear),
                Forms\Components\Select::make('value2')
                    ->required()
                    ->placeholder('YYYY')
                    ->options(array_combine($years, $years))
                    ->visible(fn(NotificationSetting $record) => $record->form_type === FormType::RangeYear),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('value'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {

                        if(!empty($data['value']))
                        {
                            if($data['form_type'] == FormType::RangeYear){
                                $years = explode('-', $data['value']);
                                $data['value1'] = $years[0];
                                $data['value2'] = $years[1];
                            }
                        }
                        
                        
                
                        return $data;
                    })
                    ->using(function (NotificationSetting $record, array $data): NotificationSetting {

                        if($record->form_type === FormType::RangeYear){

                            $record->update([
                                'value' => $data['value1'] . '-' . $data['value2']
                            ]);

                        }else{
                            $record->update([
                                'value' => $data['value']
                            ]);
                        }
                        
                
                        return $record;
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
            'index' => Pages\ListNotificationSettings::route('/'),
            //'create' => Pages\CreateNotificationSetting::route('/create'),
            //'edit' => Pages\EditNotificationSetting::route('/{record}/edit'),
        ];
    }
}
