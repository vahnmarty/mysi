<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\ApplicationResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(100);

        Filament::serving(function () {

            Filament::registerNavigationGroups([
                'Administration',
                'Configuration',
            ]);

            // Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
            //     return $builder
            //         ->groups([
            //             NavigationGroup::make('Administration')
            //                 ->items([
            //                     ...ApplicationResource::getNavigationItems(),
            //                     ...UserResource::getNavigationItems(),
            //                 ]),
            //         ]);
            // });

            Filament::registerUserMenuItems([
                UserMenuItem::make()
                    ->label('Change Password')
                    ->url(url('admin/change-password'))
                    ->icon('heroicon-s-key'),
            ]);

            Filament::registerScripts([
                asset('js/filament.js'),
            ]);

            Filament::registerViteTheme('resources/css/filament.css');

        });


    }
}
