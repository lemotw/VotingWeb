<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\Election\CandidateService;

class ElectionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('App\Service\Election\CandidateService', function($app){
            return new CandidateService;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
