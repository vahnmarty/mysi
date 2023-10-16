<?php

namespace App\Http\Controllers;

use Arr;
use App\Models\Application;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\NotificationLetter;
use App\Enums\NotificationStatusType;

class NotificationController extends Controller
{
    public function index()
    {
        $apps = Application::where('account_id', accountId())->hasNotifications()->get();

        if(count($apps) == 1) {
            return redirect()->route('notifications.show', $apps->first()->uuid);
        }
    }

    public function show($uuid)
    {
        $app = Application::with('appStatus')->whereUuid($uuid)->firstOrFail();

        $appStatus = $app->appStatus;

        $notification = NotificationLetter::where('reference', $appStatus->application_status)->first();

        if(!$notification){
            return 'notification not found';
        }

        $account = $app->account;

        $variables = [
            'application' => $app->toArray(),
            'student' => $app->student->toArray(),
            'parent' => $account->primaryParent ? $account->primaryParent->toArray() : $account->firstParent?->toArray(),
            'address' => $account->primaryAddress ? $account->primaryAddress->toArray() : $account->addresses()->first()?->toArray()
        ];


        $content = $this->parseContent($notification->content, $variables);

        return view('notifications.show', compact('app', 'account', 'content', 'notification'));
    }

    public function pdf($uuid)
    {
        $app = Application::with('appStatus')->whereUuid($uuid)->firstOrFail();

        $appStatus = $app->appStatus;

        $notification = NotificationLetter::where('reference', $appStatus->application_status)->first();

        if(!$notification){
            return 'notification not found';
        }

        $account = $app->account;

        $variables = [
            'application' => $app->toArray(),
            'student' => $app->student->toArray(),
            'parent' => $account->primaryParent ? $account->primaryParent->toArray() : $account->firstParent?->toArray(),
            'address' => $account->primaryAddress ? $account->primaryAddress->toArray() : $account->addresses()->first()?->toArray()
        ];


        $content = $this->parseContent($notification->content, $variables);

        $pdf = Pdf::loadView('notifications.letter-pdf', compact('app', 'account', 'content', 'notification'));
        return $pdf->stream('mysi-letter.pdf');
    }


    public function getNotificationLetter(Application $app, $status)
    {
        if($status == NotificationStatusType::Accepted)
        {
            $letter = NotificationLetter::where('reference', NotificationStatusType::Accepted)->first();

            //if($app->)
        }
    }

    public function sample(Request $request, $id)
    {
        $request->validate([
            'application_id' => 'required'
        ]);

        $notification = NotificationLetter::findOrFail($id);

        $app = Application::with('student', 'account')->findOrFail($request->application_id);

        $account = $app->account;

        $variables = [
            'application' => $app->toArray(),
            'student' => $app->student->toArray(),
            'parent' => $account->primaryParent ? $account->primaryParent->toArray() : $account->firstParent?->toArray(),
            'address' => $account->primaryAddress ? $account->primaryAddress->toArray() : $account->addresses()->first()?->toArray()
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

            $field = Arr::get($variables, $variableName);

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
