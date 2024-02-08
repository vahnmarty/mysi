<?php

namespace App\Services;

use App\Models\Application;
use App\Models\NotificationLetter;
use App\Models\NotificationSetting;

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
            'address' => $account->primaryAddress ? $account->primaryAddress->toArray() : $account->addresses()->first()?->toArray()
        ];

        $content = $this->parseContent($notification->content, $variables);

        $fa_content = '';

        if($appStatus->financial_aid){
            $fa_content = $this->createFinancialAidContent($app);

            $letterType .= ' with FA Letter ' . $appStatus->financial_aid; 
        }
        
        $faq_content = '';
        

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

    public function parseContent($input, $variables)
    {

        // Define a callback function to replace the variables
        $replaceCallback = function($matches) use ($variables) {
            $variableName = trim($matches[1], '{}');

            $field = \Arr::get($variables, $variableName);

            if(!empty($field)){

                if(is_date($field)){
                    return date('F d, Y', strtotime($field));
                }

                if($this->special_cases($variableName)){
                    $type = $this->special_cases($variableName, returnArray: true) ;
                    return $this->transformSpecialCases($field, $type);
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
            'timeline.acceptance_deadline_date' => 'date_description',
            'timeline.registration_start_date' => 'date_with_day',
            'timeline.registration_end_date' => 'date_description',
            'system.payment.tuition_fee' => 'money',
            'application_status.total_financial_aid_amount' => 'money',
            'application_status.annual_financial_aid_amount' => 'money',
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
    }
}