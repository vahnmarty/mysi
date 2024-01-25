<?php

namespace App\Filament\Resources\NotificationLetterResource\Pages;

use App\Models\Application;
use App\Models\NotificationLetter;
use Filament\Resources\Pages\Page;
use App\Models\NotificationSetting;
use App\Services\NotificationService;
use App\Filament\Resources\NotificationLetterResource;

class NotificationVariables extends Page
{
    protected static string $resource = NotificationLetterResource::class;

    protected static string $view = 'filament.resources.notification-letter-resource.pages.notification-variables';

    public $variables = [];

    public function mount()
    {
        $notification = NotificationLetter::first();

        $app = Application::with('student', 'account', 'appStatus')->first();

        $account = $app->account;

        $variables = [
            'application' => $app->toArray(),
            'application_status' => $app->appStatus->toArray(),
            'timeline' => NotificationSetting::get()->pluck('value', 'config')->toArray(),
            'system' => config('settings'),
            'student' => $app->student->toArray(),
            'parents_name' => $account->getParentsName(),
            'parents_name_salutation' => $account->getParentsName(withSalutation:true),
            'parent' => $account->primaryParent ? $account->primaryParent->toArray() : $account->firstParent?->toArray(),
            'address' => $account->primaryAddress ? $account->primaryAddress->toArray() : $account->addresses()->first()?->toArray()
        ];

        $this->variables = collect($variables)->sortKeys()->all();
    }

   
}
