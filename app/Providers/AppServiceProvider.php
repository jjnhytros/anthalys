<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Services\ATHDateTime\ATHDateTime;
use App\Models\Anthaleja\Phone\Application;
use App\Services\Anthaleja\City\CityPlanningService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CityPlanningService::class, function ($app) {
            return new CityPlanningService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        if (!Auth::check() && !session()->has('logged_out')) {
            Auth::loginUsingId(3);
        }

        View::composer('*', function ($view) {
            $applications = Application::where('status', 'view')->get();
            $athDateTime = new ATHDateTime();
            $athNow = $athDateTime->getAYear() . ', ' .
                $athDateTime->getADay() . '/' .
                $athDateTime->getAMonth() . ' ' .
                $athDateTime->getAHour() . ':' .
                $athDateTime->getAMinute() . ':' .
                $athDateTime->getASecond();
            $view->with([
                'applications' => $applications,
                'athNow' => $athNow,
            ]);
        });
    }
}
