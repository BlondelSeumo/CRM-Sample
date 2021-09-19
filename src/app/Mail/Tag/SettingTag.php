<?php


namespace App\Mail\Tag;


class SettingTag extends Tag
{
    protected $setting_name;

    public function __construct($setting_name, $notifier, $receiver)
    {
        $this->setting_name = $setting_name;
        $this->notifier = $notifier;
        $this->receiver = $receiver;
        $this->resourceurl = config('notification.settings_front_end_route_name');
    }
    public function notification()
    {
        return [
            '{name}' => $this->setting_name
        ];
    }
}
