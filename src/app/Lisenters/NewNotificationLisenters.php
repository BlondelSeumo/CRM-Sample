<?php

namespace App\Lisenters;

use App\Events\NewNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewNotificationLisenters
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewNotification  $event
     * @return void
     */
    public function handle(NewNotification $event)
    {
        return $event;
    }
}
