<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\FormType;
use App\Models\Setting;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SettingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SettingResource\RelationManagers;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static bool $shouldRegisterNavigation = false;

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
                    ->visible(fn(Setting $record) => $record->form_type === FormType::Date),
                Forms\Components\Select::make('value1')
                    ->required()
                    ->placeholder('YYYY')
                    ->options(array_combine($years, $years))
                    ->visible(fn(Setting $record) => $record->form_type === FormType::RangeYear),
                Forms\Components\Select::make('value2')
                    ->required()
                    ->placeholder('YYYY')
                    ->options(array_combine($years, $years))
                    ->visible(fn(Setting $record) => $record->form_type === FormType::RangeYear),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('config')
                    ->description(fn (Setting $record) => $record->description),
                Tables\Columns\TextColumn::make('value'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {

                        if($data['form_type'] == FormType::RangeYear){
                            explode('-', $data['value']);
                            $data['value'] = $data[''];
                        }
                        
                
                        return $data;
                    })
                    ->using(function (Setting $record, array $data): Setting {

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
            'index' => Pages\ListSettings::route('/'),
            //'create' => Pages\CreateSetting::route('/create'),
            //'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }    
}
