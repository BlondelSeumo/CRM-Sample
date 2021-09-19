<?php


namespace App\Hooks\Settings;


use App\Helpers\Core\Traits\InstanceCreator;
use App\Services\Setup\InstallationService;

class AfterBroadcastSettingSaved
{
    use InstanceCreator;

    public function handle()
    {
        return resolve(InstallationService::class)->finishInstallation();

    }
}
