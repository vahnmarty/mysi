<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Application;
use App\Models\NotificationLetter;
use Arr;

class NotificationPreview extends Component
{
    public $notification, $application_id;
    public $content = '';
    protected $queryString = ['application_id'];

    public function render()
    {
        return view('livewire.notification-preview')->layout('layouts.guest');
    }

    public function mount($id)
    {
        $this->notification = NotificationLetter::findOrFail($id);

        $this->app = Application::with('student', 'account')->findOrFail($this->application_id);

        $account = $this->app->account;

        $variables = [
            'application' => $this->app->toArray(),
            'student' => $this->app->student->toArray(),
            'parent' => $account->primaryParent ? $account->primaryParent->toArray() : $account->firstParent?->toArray(),
            'address' => $account->primaryAddress ? $account->primaryAddress->toArray() : $account->addresses()->first()?->toArray()
        ];


        $this->content = $this->parseContent($this->notification->content, $variables);
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
