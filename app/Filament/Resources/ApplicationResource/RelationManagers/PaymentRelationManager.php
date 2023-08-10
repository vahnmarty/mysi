<?php

namespace App\Filament\Resources\ApplicationResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\PromoCode;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PaymentRelationManager extends RelationManager
{
    protected static string $relationship = 'payment';

    protected static ?string $recordTitleAttribute = 'name_on_card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    ->reactive()
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_on_card'),
                Tables\Columns\TextColumn::make('initial_amount'),
                Tables\Columns\TextColumn::make('promo_code'),
                Tables\Columns\TextColumn::make('final_amount'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
