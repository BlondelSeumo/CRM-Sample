<?php


namespace App\Hooks\Settings;


use App\Helpers\Core\Traits\InstanceCreator;
use App\Hooks\HookContract;
use App\Services\Setup\InstallationService;

class AfterDeliverySettingSaved extends HookContract
{
    use InstanceCreator;

    public function handle()
    {
    }
}
