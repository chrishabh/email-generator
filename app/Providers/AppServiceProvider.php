<?php

namespace App\Providers;

use App\Models\EnvProperties;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
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
        $env_properties = null;  
        if (Schema::hasTable('env_properties')) {
            $env_properties = EnvProperties::enabled()->get();
        }
        

        if (!is_null($env_properties)) {
            foreach ($env_properties as $envprop) {
                        Config::set('env.'.$envprop->key, $envprop->value);
            }
        }
    }
}
