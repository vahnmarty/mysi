<?php

namespace App\Http\Controllers;

use Arr;
use App\Models\Application;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ApplicationStatus;
use App\Models\NotificationLetter;
use App\Models\NotificationMessage;
use App\Models\NotificationSetting;
use App\Enums\NotificationStatusType;

class NotificationController extends Controller
{
    public function index()
    {
        $apps = Application::where('account_id', accountId())->get();

        if(count($apps) == 1) {
            return redirect()->route('notifications.show', $apps->first()->uuid);
        }
    }

    public function show($uuid)
    {
        $notification = NotificationMessage::whereUuid($uuid)->firstOrFail();
        $app = $notification->application;
        $appStatus = $app->appStatus;
        $content = $notification->content;

        $faq = '';

        if($appStatus->application_status == NotificationStatusType::WaitListed){
            $faq_cms = NotificationLetter::where('title', 'Waitlist FAQ')->first();

            if($faq_cms){
                $faq = $faq_cms->content;
            }
        }

        $this->readNotification($appStatus);

        return view('notifications.show', compact('notification', 'app', 'content', 'appStatus', 'faq'));
    }

    public function readNotification(ApplicationStatus $appStatus)
    {
        if(!$appStatus->notification_read){
            $appStatus->notification_read = true;
            $appStatus->notification_read_date = now();
            $appStatus->save();
        }
    }

    public function pdf($uuid)
    {
        $notification = NotificationMessage::with('application')->whereUuid($uuid)->firstOrFail();
        
        $content = $notification->content;

        $pdf = Pdf::loadView('notifications.letter-pdf', compact('content', 'notification'));

        return $pdf->stream('mysi-letter.pdf');
    }

    public function financialAid($uuid)
    {
        $app = Application::with('appStatus')->whereUuid($uuid)->firstOrFail();

        $account = $app->account;

        $variables = [
            'application' => $app->toArray(),
            'student' => $app->student->toArray(),
            'parent' => $account->primaryParent ? $account->primaryParent->toArray() : $account->firstParent?->toArray(),
            'address' => $account->primaryAddress ? $account->primaryAddress->toArray() : $account->addresses()->first()?->toArray()
        ];

        $content = $variables;

        return view('notifications.financial-aid', compact('app', 'account', 'content'));
    }


    public function getNotificationLetter(Application $app, $status)
    {
        if($status == NotificationStatusType::Accepted)
        {
            $letter = NotificationLetter::where('reference', NotificationStatusType::Accepted)->first();

            //if($app->)
        }
    }

    public function sample(Request $request, $letterId)
    {
        $request->validate([
            'application_id' => 'required'
        ]);

        $app = Application::findOrFail($request->application_id);

        $appStatus = $app->appStatus;

        $notification = NotificationLetter::find($letterId);

        $account = $app->account;

        $variables = [
            'timeline' => NotificationSetting::get()->pluck('value', 'config')->toArray(),
            'system' => config('settings'),
            'parents_name' => $account->getParentsName(),
            'parents_name_salutation' => $account->getParentsName(withSalutation:true),
            'student' => $app->student->toArray(),
            'application' => $app->toArray(),
            'application_status' => $app->appStatus->toArray(),
            'parent' => $account->primaryParent ? $account->primaryParent->toArray() : $account->firstParent?->toArray(),
            'address' => $account->primaryAddress ? $account->primaryAddress->toArray() : $account->addresses()->first()?->toArray(),
            'class_list' => $app->classList()
        ];

        $content = $this->parseContent($notification->content, $variables);

        if($request->pdf){
            $pdf = Pdf::loadView('notifications.letter-pdf', compact('app', 'account', 'content', 'notification'));
            return $pdf->stream('mysi-letter.pdf');
        }

        return view('notifications.letter-preview', compact('app', 'account', 'content', 'notification'));
    }

    public function parseContent($input, $variables)
    {

        // Define a callback function to replace the variables
        $replaceCallback = function($matches) use ($variables) {
            $variableName = trim($matches[1], '{}');

            $field = \Arr::get($variables, $variableName);

            if(!empty($field)){


                if($variableName == 'class_list')
                {
                    $classes = $field;
                    if(is_array($field))
                    {
                        $html = '<ul style="padding-left: 15px; list-style: disc;">';
                        
                        if(count($classes)){
                            foreach($classes as $class)
                            {
                                $html .= '<li><strong>' . $class . '</strong></li>';
                            }
                        }else{

                            $html .= '<li><strong>No class information.</strong></li>';
                        }
                        

                        $html .= '</ul>';

                        return $html;
                    }
                    return '<strong>CLASS</strong>';
                }else{
    
                    if($this->special_cases($variableName)){
                        $type = $this->special_cases($variableName, returnArray: true) ;
                        return $this->transformSpecialCases($field, $type);
                    }

                    if(is_date($field)){
                        return date('F d, Y', strtotime($field));
                    }
                }

                

                return $field;
            }
            
            //return ''; // leaving blank??

            return $matches[0]; // If the variable doesn't exist in the array, leave it unchanged
        };

        // Use regular expression to find and replace variables
        $outputString = preg_replace_callback('/@{([^}]+)}/', $replaceCallback, $input);

        return $outputString;
    }

    public function special_cases($input, $returnArray = false)
    {
        $array = [
            //'timeline.notification_date' => 'date_with_day',
            'timeline.acceptance_deadline_date' => 'date_description',
            'timeline.registration_start_date' => 'date_timezone_display',
            'timeline.registration_end_date' => 'date_description',
            'system.payment.tuition_fee' => 'number',
            'application_status.total_financial_aid_amount' => 'number',
            'application_status.annual_financial_aid_amount' => 'number',
            'application_status.deposit_amount' => 'number',
            'system.number_of_applicants' => 'number'

        ];
        
        if($returnArray){
            return isset($array[$input]) ? $array[$input] : [];
        }
        
        return isset($array[$input]);
    }

    public function transformSpecialCases($input, $type)
    {
        if($type == 'date_description'){
            return date(('g:i a T \o\n F j, Y'), strtotime($input));
        }

        if($type == 'date_with_day'){
            return date(('l, F j, Y'), strtotime($input));
        }

        if($type == 'date_timezone_display'){
            return date(('F j, Y'), strtotime($input)) . ' at ' . date(('g a T'), strtotime($input));
        }

        if($type == 'money'){
            return number_format($input, 2);
        }

        if($type == 'number'){
            return number_format($input);
        }
    }
}
