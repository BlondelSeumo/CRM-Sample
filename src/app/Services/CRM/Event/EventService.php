<?php


namespace App\Services\CRM\Event;


use App\Models\CRM\Event\Event;
use App\Services\ApplicationBaseService;

class EventService extends ApplicationBaseService
{
    public function __construct(Event $event)
    {
        $this->model = $event;
    }

}
