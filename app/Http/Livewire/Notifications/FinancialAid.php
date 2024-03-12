<?php

namespace App\Http\Livewire\Notifications;

use Arr;
use Livewire\Component;
use App\Models\Application;
use App\Models\NotificationLetter;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Concerns\InteractsWithForms;

class FinancialAid extends Component implements HasForms
{
    use InteractsWithForms;
    
    public $checked;
    public $contents;
    const FinancialAid = 'Financial Aid';

    public function mount($uuid)
    {
        $app = Application::where('uuid', $uuid)->firstOrFail();

        $notification = NotificationLetter::where('title', self::FinancialAid)->first();

        $account = $app->account;

        $variables = [
            'application' => $app->toArray(),
            'student' => $app->student->toArray(),
            'parent' => $account->primaryParent ? $account->primaryParent->toArray() : $account->firstParent?->toArray(),
            'address' => $account->primaryAddress ? $account->primaryAddress->toArray() : $account->addresses()->first()?->toArray()
        ];


        $this->contents = $this->parseContent($notification->content, $variables);
    }
    
    public function render()
    {
        return view('livewire.notifications.financial-aid');
    }

    public function getFormSchema()
    {
        return [
            Checkbox::make('checked')
                ->columnSpan('full')
                ->label('By checking the box, I acknowledged the Financial Aid')
                ->lazy()
                ->required()
        ];
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
