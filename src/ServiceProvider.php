<?php

namespace AragornYang\AliyunSms;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__ . '/config/aliyun-sms.php' => config_path('aliyun-sms.php'),]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/aliyun-sms.php', 'aliyun-sms');
        $this->app->singleton(AliyunSms::class, AliyunSms::class);
    }
}