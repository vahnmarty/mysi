<?php

namespace App\Filament\Widgets;

use Auth;
use DB;
use Filament\Widgets\Widget;
use App\Enums\NotificationStatusType;
use App\Filament\Widgets\Widget\EmptyCard;
use App\Filament\Widgets\Widget\GroupWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class FinancialAidSummary extends GroupWidget
{
    public $title = 'Financial Aid Summary';

    protected static ?string $pollingInterval = null;

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getCards(): array
    {
        $types = ['A', 'B', 'C', 'D'];

        $cards = [];

        if( Auth::user()->hasAnyRole(['admin', 'staff']))
        {
            foreach($types as $type)
            {
                $cards[] = Card::make($type . ' - Total # Recipients', $this->getTotalRecipients($type));
                $cards[] = Card::make($type . ' - Letters Read', $this->getLettersRead($type));
                $cards[] = Card::make($type . ' - Total # Accepted Enrollment', 
                            DB::table('application_status')
                            ->where('financial_aid', 'LIKE', '%'.$type.'%')
                            ->whereNotNull('fa_acknowledged_at')
                            ->where('candidate_decision_status', 'Accepted')
                            ->count()
                        );
                $cards[] = Card::make($type . ' - Total $ Value', $this->getAnnualFinancialAidAmountTotal($type));
                $cards[] = Card::make($type . ' -  $ Value Accepted Enrollment', $this->getAnnualFinancialAidAmountTotalAccepted($type));
                $cards[] = EmptyCard::make('z', 0);
            }

            $cards[] = Card::make('E - Total', $this->getTotalRecipients('E'));
            $cards[] = Card::make('E - Letters Read', $this->getLettersRead('E'));
            $cards[] = EmptyCard::make('z', 0);

        }


        $fas = ['A', 'B', 'B1', 'C', 'D'];

        $cards[] = Card::make('Total # Recipients', 
                    DB::table('application_status')
                        ->whereIn('financial_aid', $fas)
                        ->count()
                    );
        $cards[] = Card::make('Total # Accepted Enrollment', 
                    DB::table('application_status')
                        ->whereIn('financial_aid', $fas)
                        ->where('candidate_decision_status', 'Accepted')
                        ->count()
                    );
        $cards[] = Card::make('Total # Declined Enrollment', 
                    DB::table('application_status')
                        ->whereIn('financial_aid', $fas)
                        ->where('candidate_decision_status', 'Declined')
                        ->count()
                    );
        $cards[] = Card::make('Total $ Value', 
                    '$' . number_format(DB::table('application_status')
                        ->whereIn('financial_aid', $fas)
                        ->sum('annual_financial_aid_amount'))
                    );
        $cards[] = Card::make('$ Value Accepted Enrollment', 
                    '$' . number_format(DB::table('application_status')
                        ->whereIn('financial_aid', $fas)
                        ->where('candidate_decision_status', 'Accepted')
                        ->sum('annual_financial_aid_amount'))
                    );
        $cards[] = Card::make('$ Value Declined Enrollment', 
                    '$' . number_format(DB::table('application_status')
                        ->whereIn('financial_aid', $fas)
                        ->where('candidate_decision_status', 'Declined')
                        ->sum('annual_financial_aid_amount'))
                    );

        return $cards;
    }

    public function getTotalRecipients($type)
    {
        return DB::table('application_status')
            ->where('financial_aid', 'LIKE', '%'.$type.'%')
            ->count();
    }

    public function getLettersRead($type)
    {
        return DB::table('application_status')
            ->where('financial_aid', 'LIKE', '%'.$type.'%')
            ->whereNotNull('fa_acknowledged_at')
            ->count();
    }

    public function getAnnualFinancialAidAmountTotal($type)
    {
        $sum = DB::table('application_status')
        ->where('financial_aid', 'LIKE', '%'.$type.'%')
        ->sum('annual_financial_aid_amount');

        return '$' . number_format($sum);
    }


    public function getAnnualFinancialAidAmountTotalAccepted($type)
    {
        $sum = DB::table('application_status')
        ->where('financial_aid', 'LIKE', '%'.$type.'%')
        ->whereNotNull('fa_acknowledged_at')
        ->where('candidate_decision_status', 'Accepted')
        ->sum('annual_financial_aid_amount');

        return '$' . number_format($sum);
    }
}
