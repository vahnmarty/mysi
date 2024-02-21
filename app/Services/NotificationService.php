<?php

namespace App\Services;

use App\Models\Application;
use App\Models\NotificationLetter;
use App\Models\NotificationSetting;
use App\Enums\NotificationStatusType;

class NotificationService{

    public function createMessage(Application $app)
    {
        $appStatus = $app->appStatus;

        $letterType = $appStatus->application_status;

        if($appStatus->application_status == 'Accepted'){
            if($appStatus->withHonors()){
                $letterType = 'Accepted with Honors';
            }
        }

        $notification = NotificationLetter::where('title', $letterType)->first();

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

        $fa_content = '';
        $faq_content = '';

        if($appStatus->financial_aid){
            $fa_content = $this->createFinancialAidContent($app);
            $letterType .= ' with FA Letter ' . $appStatus->financial_aid; 
        }

        if($appStatus->application_status == NotificationStatusType::WaitListed){
            $faq_content = $this->createFAQContent($app);
        }
        

        $app->notificationMessage()->create([
            'account_id' => $app->account_id,
            'content' => $content,
            'financial_aid_content' => $fa_content,
            'faq_content' => $faq_content,
        ]);

        return $letterType;
    }

    public function createFinancialAidContent(Application $app)
    {
        $notification = NotificationLetter::where('title', 'FA Letter ' . $app->appStatus->financial_aid)->first();

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
            'address' => $account->primaryAddress ? $account->primaryAddress->toArray() : $account->addresses()->first()?->toArray()
        ];


        $contents = $this->parseContent($notification->content, $variables);

        return $contents;
    }

    public function createFAQContent(Application $app)
    {
        $notification = NotificationLetter::where('title', 'Waitlist FAQ')->first();

        $account = $app->account;

        $variables = [
            
        ];

        $contents = $this->parseContent($notification->content, $variables);

        return $contents;
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
            'timeline.registration_start_date' => 'date_with_day',
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

        if($type == 'money'){
            return number_format($input, 2);
        }

        if($type == 'number'){
            return number_format($input);
        }
    }
}