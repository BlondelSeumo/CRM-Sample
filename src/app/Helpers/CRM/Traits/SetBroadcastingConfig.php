<?php


namespace App\Helpers\CRM\Traits;


use App\Helpers\Core\Traits\InstanceCreator;
use App\Services\Core\Setting\DeliverySettingService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class SetBroadcastingConfig
{
    use InstanceCreator;

    public function cashClear()
    {
        Artisan::call('config:clear');
        return $this;
    }

    public function configSet()
    {
        $default = resolve(DeliverySettingService::class)->getDefaultSettings($key = 'default_broadcast');
        $broadcast = resolve(DeliverySettingService::class)->getFormattedDeliverySettings([optional($default)->value, 'default_broadcast_driver_name']);

        if ($broadcast) {
            Config::set('services.broadcast_custom_driver', $broadcast['broadcast_driver']);
            if ($broadcast['broadcast_driver'] == 'pusher') {
                Config::set('broadcasting.default', $broadcast['broadcast_driver']);
                Config::set('broadcasting.connections.pusher.key', $broadcast['pusher_app_key']);
                Config::set('broadcasting.connections.pusher.secret', $broadcast['pusher_app_secret']);
                Config::set('broadcasting.connections.pusher.app_id', $broadcast['pusher_app_id']);
                Config::set('broadcasting.connections.pusher.options.cluster', $broadcast['pusher_app_cluster']);

            } elseif ($broadcast['broadcast_driver'] == 'ajax') {
                Config::set('services.broadcast_custom_driver', $broadcast['broadcast_driver']);
            }
        }

    }
}
