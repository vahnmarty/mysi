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
use App\Models\NotificationLetter;
use App\Enums\CandidateDecisionType;
use App\Enums\NotificationStatusType;
use App\Services\NotificationService;
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
                    ->color('secondary')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ?? '-- N/A --')
                    ->action(
                        Tables\Actions\Action::make('update_status')
                            ->disabled(fn(Application $record) => $record->appStatus?->candidate_decision)
                            ->requiresConfirmation()
                            ->modalHeading('Update Notification Status')
                            ->modalSubheading('The applicant will receive a notification once you confirm.')
                            ->modalButton('Save')
                            ->mountUsing(fn (Forms\ComponentContainer $form, Application $record) => $form->fill([
                                'application_status' => $record->appStatus?->application_status,
                                'math_class' => $record->appStatus->math_class,
                                'english_class' => $record->appStatus->english_class,
                                'bio_class' => $record->appStatus->bio_class,
                            ]))
                            ->form([
                                Forms\Components\Select::make('application_status')
                                    ->label('Status')
                                    ->options(NotificationStatusType::asSelectArray())
                                    ->required()
                                    ->reactive(),
                                Forms\Components\Grid::make(1)
                                    ->reactive()
                                    ->visible(fn(Closure $get) => $get('application_status') == NotificationStatusType::Accepted)
                                    ->schema([
                                        Forms\Components\Toggle::make('with_honors')
                                            ->label('With Honors?')
                                            ->disabled()
                                            ->onColor('success')
                                            ->offColor('secondary')
                                            ->onIcon('heroicon-s-check')
                                            ->offIcon('heroicon-s-x')
                                            ->afterStateHydrated(function (Forms\Components\Toggle $component, Application $record) {
                                                $component->state($record->appStatus?->withHonors() );
                                            }),
                                            Forms\Components\Toggle::make('with_fa')
                                            ->label('With Financial Aid?')
                                            ->disabled()
                                            ->onColor('success')
                                            ->offColor('secondary')
                                            ->onIcon('heroicon-s-check')
                                            ->offIcon('heroicon-s-x')
                                            ->afterStateHydrated(function (Forms\Components\Toggle $component, Application $record) {
                                                $component->state($record->appStatus?->withFA() );
                                            }),
                                        Forms\Components\TextInput::make('math_class')
                                            ->label('Math Class')
                                            ->disabled()
                                            ->visible(fn($state) => !empty($state)),
                                        Forms\Components\TextInput::make('english_class')
                                            ->label('English Class')
                                            ->disabled()
                                            ->visible(fn($state) => !empty($state)),
                                        Forms\Components\TextInput::make('bio_class')
                                            ->disabled()
                                            ->label('Bio Class')
                                            ->disabled()
                                            ->visible(fn($state) => !empty($state)),
                                    ])
                            ])
                            ->action(function (Application $record, $data): void {
                                
                                $application_status = $data['application_status'];

                                $appStatus = $record->appStatus;
                                $appStatus->application_status = $application_status;
                                $appStatus->candidate_decision_status = CandidateDecisionType::NotificationSent;
                                $appStatus->notification_read = false;
                                //$appStatus->notification_read_date = null; // If it's a new notification, must be unread.
                                $appStatus->save();

                                $record->notificationMessages()->delete();

                                if($data['application_status'] != NotificationStatusType::NoResponse)
                                {
                                    $service = new NotificationService;
                                    $letterType = $service->createMessage($record);

                                    Notification::make()
                                        ->title('Notification Set.')
                                        ->body('Status: ' . $letterType )
                                        ->success()
                                        ->send();
                                }
                                
                            }),
                        ),
                Tables\Columns\ToggleColumn::make("appStatus.honors_english")
                    ->label('Honors Eng'),
                Tables\Columns\ToggleColumn::make("appStatus.honors_math")
                    ->label('Honors Math'),
                Tables\Columns\ToggleColumn::make("appStatus.honors_bio")
                    ->label('Honors Bio'),
                Tables\Columns\SelectColumn::make("appStatus.financial_aid")
                    ->label('With F/A')
                    ->options(['A' => 'A', 'B' => 'B', 'B1' => 'B1', 'C' => 'C', 'D' => 'D', 'E' => 'E']),
                Tables\Columns\TextColumn::make("appStatus.deposit_amount")
                    ->label('Deposit Amount'),
                Tables\Columns\TextColumn::make("appStatus.candidate_decision_status")
                    ->label('Decision'),
                Tables\Columns\TextColumn::make("appStatus.candidate_decision_date")
                    ->label('Decision Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('App Status')
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
                Tables\Filters\SelectFilter::make('notification_status')
                    ->label('Notification Status')
                    ->options(NotificationStatusType::asSameArray())
                    ->query(function (Builder $query, array $data){
                        if($data['value']){
                            return $query->whereHas('appStatus', function($q) use ($data){
                                $q->where('application_status', $data['value']);
                            });
                        }
                        return $query;
                    }),
                Tables\Filters\TernaryFilter::make('notification_read')
                    ->label('Notification Read')
                    ->queries(
                        true: fn (Builder $query) => $query->notificationRead(),
                        false: fn (Builder $query) => $query->notificationRead(false),
                    ),
                Tables\Filters\SelectFilter::make('candidate_decision_status')
                    ->label('Candidate Status')
                    ->options([
                        'Accepted' => 'Accepted',
                        'Declined' => 'Declined'
                    ])
                    ->query(function (Builder $query, array $data){
                        if($data['value'] == 'Accepted'){
                            return $query->enrolled();
                        }

                        if($data['value'] == 'Declined'){
                            return $query->declinedAcceptance();
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
