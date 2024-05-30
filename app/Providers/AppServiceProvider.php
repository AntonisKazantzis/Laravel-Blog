<?php

namespace App\Providers;

use Filament\Forms;
use Filament\Tables;
use Illuminate\Support\ServiceProvider;

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
        Forms\Components\Select::configureUsing(function ($component): void {
            if (method_exists($component, 'native')) {
                $component->native(false);
            }
        });

        Tables\Filters\SelectFilter::configureUsing(function ($filter): void {
            if (method_exists($filter, 'native')) {
                $filter->native(false);
            }
        });

        Forms\Components\Component::configureUsing(function ($component): void {
            if (method_exists($component, 'native')) {
                $component->native(false);
            }
        });
    }
}
