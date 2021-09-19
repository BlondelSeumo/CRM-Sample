<?php

namespace App\Providers;

use App\Helpers\CRM\Traits\SetBroadcastingConfig;
use App\Helpers\CRM\Traits\SetMailConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Services\CRM\Settings\SettingsService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (!$this->app->environment('production') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        }
    }

    public function boot()
    {
        Schema::defaultStringLength(191);
        /*
         * Application locale defaults for various components
         *
         * These will be overridden by LocaleMiddleware if the session local is set
         */

        // setLocale for php. Enables ->formatLocalized() with localized values for dates
        setlocale(LC_TIME, config('app.locale_php'));

        Carbon::setLocale(config('app.locale'));

        if (!app()->runningInConsole()) {
            if (config('locale.languages')[config('app.locale')][2]) {
                session(['lang-rtl' => true]);
            } else {
                session()->forget('lang-rtl');
            }
        }

        try {
            $settings = resolve(SettingsService::class)->getCachedFormattedSettings();
            View::composer('*', function ($view) use ($settings) {
                $view->with('settings', $settings);
            });

            foreach ($settings as $key => $setting) {
                if ($key == 'company_name') {
                    config()->set('app.name', $setting);
                }
                config()->set('settings.application.' . $key, $setting);
            }
        } catch (\Exception $exception) {
        }

        // Mail Setting Config
        if (!env('MARKET_PLACE_VERSION', false)) {
            try {
                SetMailConfig::new(true)
                    ->clear()
                    ->set();
            } catch (\Exception $exception) {
            }

            try {
                SetBroadcastingConfig::new(true)
                    ->cashClear()
                    ->configSet();
            } catch (\Exception $exception) {
            }
        }
    }
}
