<?php

namespace App\Providers;

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
//        # 开发环境加载
//        if($this->app->environment() == 'local'){
//            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
//            $this->app->register(\Barryvdh\Debugbar\Facade::class);
//        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
