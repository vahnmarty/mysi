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
use Filament\Notifications\Notification;
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
                    ->label("Student First Name")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("student.last_name")
                    ->label("Student Last Name")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("account.user.email")
                    ->label("Parent Email")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("account.user.phone")
                    ->label("Parent Phone")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("status"),
                Tables\Columns\TextColumn::make("with_honors")
                    ->label('With Honors'),
                Tables\Columns\TextColumn::make("with_fa")
                    ->label('With F/A'),
                Tables\Columns\TextColumn::make("deposit_amount")
                    ->label('Deposit Amount'),
                Tables\Columns\TextColumn::make("decision")
                    ->label('Decision'),
                Tables\Columns\TextColumn::make("print")
                    ->label('Print'),
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
                Tables\Actions\ViewAction::make()->label('View App'),
                Tables\Actions\Action::make('discount')
                    ->label('Reduce App Fee')
                    ->mountUsing(function(Forms\ComponentContainer $form, Application $record){
                        return $form->fill([
                            'initial_amount' => $record->payment?->initial_amount ?? 100
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
                            ->disabled(),
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
                        if($record->payment){
                            $record->payment()->update([
                                'promo_code' => $data['promo_code'],
                                'initial_amount' => $data['final_amount'],
                                'final_amount' => $data['final_amount'],
                            ]);
                        }else{
                            $user = $record->account?->user;

                            if(!empty($user)){
                                $record->payment()->create([
                                    'user_id' => $user->id,
                                    'promo_code' => $data['promo_code'],
                                    'initial_amount' => $data['final_amount'],
                                    'final_amount' => $data['final_amount'],
                                ]);
                            }else{

                                Notification::make()
                                ->title('Error! User does not exist')
                                ->danger()
                                ->send();
                            }
                            
                        }
                        
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
