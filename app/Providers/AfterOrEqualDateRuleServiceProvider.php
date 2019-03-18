<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Validator;

class AfterOrEqualDateRuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('after_or_equal_date', function($attribute, $value, $parameters, $validator) {
            $date = array_get($validator->getData(),$parameters[0]);
            return $this->after_or_equal_date($value,$date);
        });
    }

    public function after_or_equal_date($value,$date)
    {
        # code...
       
        if (Carbon::parse($value)->gte(Carbon::parse($date))){
            return true;
        }
        return false;
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
