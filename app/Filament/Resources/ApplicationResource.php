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
use App\Enums\NotificationStatusType;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Notifications\Admission\ApplicationReviewed;
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
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->join('accounts', 'applications.account_id', '=', 'accounts.id')
                            ->join('users', 'users.account_id', '=', 'accounts.id')
                            ->orderBy('users.email', $direction);
                    }),
                Tables\Columns\TextColumn::make("account.user.phone")
                    ->label("Parent Phone")
                    ->formatStateUsing(fn($state) => format_phone($state))
                    ->searchable()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->join('accounts', 'applications.account_id', '=', 'accounts.id')
                            ->join('users', 'users.account_id', '=', 'accounts.id')
                            ->orderBy('users.phone', $direction);
                    }),
                Tables\Columns\TextColumn::make("status")->label('App Status'),
                Tables\Columns\TextColumn::make('appStatus.application_status')
                    ->label('Notification Status')
                    ->action(
                        Tables\Actions\Action::make('update_status')
                            ->requiresConfirmation()
                            ->modalHeading('Update Notification Status')
                            ->modalSubheading('The applicant will receive a notification once you confirm.')
                            ->modalButton('Send Notification')
                            ->mountUsing(fn (Forms\ComponentContainer $form, Application $record) => $form->fill([
                                'application_status' => $record->appStatus?->application_status,
                            ]))
                            ->form([
                                Forms\Components\Select::make('application_status')
                                    ->label('Status')
                                    ->options(NotificationStatusType::asSelectArray())
                                    ->required(),
                            ])
                            ->action(function (Application $record, $data): void {
                                $appStatus = $record->appStatus;
                                $appStatus->application_status = $data['application_status'];
                                $appStatus->save();

                                $account = $record->account;

                                foreach($account->users as $user){
                                    $user->notify(new ApplicationReviewed);
                                }
                            }),
                        ),
                Tables\Columns\ToggleColumn::make("appStatus.honors_english")
                    ->label('Honors Eng'),
                Tables\Columns\ToggleColumn::make("appStatus.honors_math")
                    ->label('Honors Math'),
                Tables\Columns\ToggleColumn::make("appStatus.honors_bio")
                    ->label('Honors Bio'),
                Tables\Columns\ToggleColumn::make("with_financial_aid")
                    ->label('With F/A'),
                Tables\Columns\TextColumn::make("deposit_amount")
                    ->label('Deposit Amount'),
                Tables\Columns\TextColumn::make("appStatus.candidate_decision")
                    ->label('Decision'),
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
                            'initial_amount' => $record->payment?->initial_amount ?? config('settings.payment.application_fee')
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
                Tables\Actions\Action::make('print')
                    ->label('Print Letter')
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
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
