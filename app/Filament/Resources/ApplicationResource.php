<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\PromoCode;
use App\Models\Application;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\RelationManagers;

class ApplicationResource extends Resource
{
    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public $status = 'submitted';

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
                    ->formatStateUsing(fn(Application $record) => $record->student?->getFullName())
                    ->searchable(),
                Tables\Columns\TextColumn::make("record_type"),
                Tables\Columns\TextColumn::make("appStatus.application_submit_date")
                    ->label('Date Submitted')
                    ->dateTime(),
                Tables\Columns\TextColumn::make("payment.final_amount"),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'submitted' => 'Submitted',
                        'incomplete' => 'Incomplete',
                    ])
                    ->query(function (Builder $query, array $data){
                        if($data['value'] == 'submitted'){
                            return $query->submitted();
                        }

                        if($data['value'] == 'incomplete'){
                            return $query->incomplete();
                        }

                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('discount')
                    ->label('Apply Promo')
                    ->mountUsing(function(Forms\ComponentContainer $form, Application $record){
                        return $form->fill([
                            'initial_amount' => $record->payment?->initial_amount
                        ]);
                    })
                    ->form([
                        Forms\Components\Select::make('promo_code')
                            ->label('Promo Code')
                            ->options(PromoCode::get()->pluck('code', 'code'))
                            ->required()
                            ->columnSpan('full')
                            ->reactive()
                            ->afterStateUpdated(function(Closure $get, Closure $set, $state){
                                $promoCode = PromoCode::where('code', $state)->first();
        
                                if($promoCode){
                                    $set('promo_amount', $promoCode->amount);
                                    $set('final_amount', $get('promo_amount'));
                                }
                            }),
                        Forms\Components\TextInput::make('initial_amount')
                            ->lazy()
                            ->label('Initial Amount')
                            ->disabled()
                            ->required(),
                        Forms\Components\TextInput::make('final_amount')
                            ->reactive()
                            ->label('Final Amount')
                            ->disabled()
                            ->required()
                            ->afterStateHydrated(function(Closure $get, Closure $set, $state){
                                $set('final_amount', $get('promo_amount'));
                            }),
                    ])
                    ->action(function(Application $record, array $data){
                        $record->payment()->update([
                            'promo_code' => $data['promo_code'],
                            'initial_amount' => $data['final_amount'],
                            'final_amount' => $data['final_amount'],
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Apply Promo?')
                    ->modalButton('Apply Promo Code'),
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\PaymentRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'view' => Pages\ViewApplication::route('/{record}'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }    
}
