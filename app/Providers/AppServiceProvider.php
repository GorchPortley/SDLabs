<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Maicol07\SSO\Flarum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Flarum::class, function ($app) {
            return new Flarum([
                'url' => env('FORUM_URL'),
                'root_domain' => env('TEMP_APP_URL'),
                'api_key' => env('FORUM_API_KEY'),
                'password_token' => env('PASSWORD_TOKEN'),
                'remember' => true,
                'verify_ssl' => env('FORUM_VERIFY_SSL', true),
                'cookies_prefix' => 'flarum',
            ]);
        });}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment() == 'production') {
            $this->app['request']->server->set('HTTPS', true);
        }

        $this->setSchemaDefaultLength();

        Validator::extend('base64image', function ($attribute, $value, $parameters, $validator) {
            $explode = explode(',', $value);
            $allow = ['png', 'jpg', 'svg', 'jpeg'];
            $format = str_replace(
                [
                    'data:image/',
                    ';',
                    'base64',
                ],
                [
                    '', '', '',
                ],
                $explode[0]
            );

            // check file format
            if (!in_array($format, $allow)) {
                return false;
            }

            // check base64 format
            if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
                return false;
            }

            return true;
        });
    }

    private function setSchemaDefaultLength(): void
    {
        try {
            Schema::defaultStringLength(191);
        }
        catch (\Exception $exception){}
    }
}
