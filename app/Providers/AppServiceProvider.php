<?php

namespace App\Providers;

use App\Models\Theme;
use App\Models\Slider;
use App\Models\Background;
use App\Models\Cantact;
use App\Models\Category;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('front.layouts.slider', function ($view) {
        $sliders = Slider::all();
        $view->with(['sliders' => $sliders]);
        });



    }
}
