<?php

namespace App\Events;

use App\Models\Core\Auth\User;
use App\Models\CRM\Notification\ExtendedNotification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User that sent the message.
     *
     * @var User
     */
    public $user;

    /**
     * Message details.
     *
     * @var Message
     */
    public $message;

    public $deal;

    /**
     * Create a new event instance.
     */
    public function __construct($action, $where, $deal)
    {
        $this->user = \Auth::user();
        $this->message = $this->user->full_name . ' ' . $action . ' ' . $where;
        $this->deal = $deal;

        ExtendedNotification::create([
            'contextable_type' => get_class($this->deal),
            'contextable_id' => $this->deal->id,
            'user_id' => $this->user->id,
            'audience' => $this->audience(),
            'message' => $this->message,

        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('pipex_notification_for_admin'),
            new PrivateChannel('pipex_notification_for_manager'),
            new PrivateChannel('pipex_notification_for_agent.' . $this->deal->owner_id),
            new PrivateChannel('pipex_notification_for_client.' . $this->deal->clientUserID())
        ];
    }

    public function audience()
    {
        $admin = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['App Admin', 'Manager']);
        })->pluck('id')->toArray();;

        $clientOwnerId = $this->deal->clientUserID() ? $this->deal->clientUserID() . "," . $this->deal->owner_id : $this->deal->owner_id;
        $ex = explode(',', $clientOwnerId);

        $merge = array_merge($admin, $ex);
        $unique = array_unique($merge);

        return implode(',', $unique);
    }
}
