<?php

namespace App\Providers\Common;

use App\Services\Settings\SettingService;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $settings = resolve(SettingService::class)
                ->getCachedFormattedSettings();

            foreach ($settings as $key => $setting) {
                if ($key == 'app_name' && $setting) {
                    config()->set('app.name', $setting);
                }else{
                    if ($setting) {
                        config()->set('settings.application.'.$key, $setting);
                    }
                }
            }
        }catch (\Exception $exception) {}
    }
}
