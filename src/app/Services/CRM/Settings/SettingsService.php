<?php


namespace App\Services\CRM\Settings;
use App\Repositories\Core\Setting\SettingRepository;
use App\Services\Core\Setting\SettingService;

class SettingsService extends SettingService
{
    public function getCachedFormattedSettings()
    {
        return cache()->remember('app-settings-global', 84000, function () {
            return resolve(SettingRepository::class)
                ->getFormattedSettings('app');
        });
    }
}
