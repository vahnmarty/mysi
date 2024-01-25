<?php

namespace App\Services;

use App\Models\Application;
use App\Models\NotificationLetter;

class NotificationService{

    public function createMessage(Application $app)
    {
        $appStatus = $app->appStatus;

        $notification = NotificationLetter::where('reference', $appStatus->application_status)->first();

        $account = $app->account;

        $variables = [
            'application' => $app->toArray(),
            'settings' => NotificationSetting::get()->pluck('value', 'config')->toArray(),
            'student' => $app->student->toArray(),
            'parent' => $account->primaryParent ? $account->primaryParent->toArray() : $account->firstParent?->toArray(),
            'address' => $account->primaryAddress ? $account->primaryAddress->toArray() : $account->addresses()->first()?->toArray()
        ];

        $content = $this->parseContent($notification->content, $variables);
        $fa_content = $this->createFinancialAidContent($app);
        $faq_content = '';
        

        $app->notificationMessage()->create([
            'account_id' => $app->account_id,
            'content' => $content,
            'financial_aid_content' => $fa_content,
            'faq_content' => $faq_content,
            'with_fa' => $app->with_financial_aid
        ]);
    }

    public function createFinancialAidContent(Application $app)
    {
        $notification = NotificationLetter::where('title', 'Financial Aid')->first();

        $account = $app->account;

        $variables = [
            'application' => $app->toArray(),
            'student' => $app->student->toArray(),
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
                return $field;
            }

            return $matches[0]; // If the variable doesn't exist in the array, leave it unchanged
        };

        // Use regular expression to find and replace variables
        $outputString = preg_replace_callback('/@{([^}]+)}/', $replaceCallback, $input);

        return $outputString;
    }
}