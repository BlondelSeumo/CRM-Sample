<?php

namespace App\Observers;

use App\Events\DiscussionCreated;
use App\Events\DiscussionDeleted;
use App\Events\DiscussionUpdated;
use App\Events\NewNotification;
use App\Models\CRM\Discussion\Discussion;

class DiscussionObserver
{
    /**
     * Handle the Discussion "created" event.
     *
     * @param  \App\Models\CRM\Discussion\Discussion  $discussion
     * @return void
     */
    public function created(Discussion $discussion)
    {
        $deal= $discussion->commentable()->first();
        broadcast(new NewNotification('commented', 'on a deal',$deal))->toOthers();
    }

    /**
     * Handle the Discussion "updated" event.
     *
     * @param  \App\Models\CRM\Discussion\Discussion  $discussion
     * @return void
     */
    public function updated(Discussion $discussion)
    {
        $deal= $discussion->commentable()->first();
        broadcast(new NewNotification('updated comment', 'on a deal',$deal))->toOthers();
    }

    /**
     * Handle the Discussion "deleted" event.
     *
     * @param  \App\Models\CRM\Discussion\Discussion  $discussion
     * @return void
     */
    public function deleted(Discussion $discussion)
    {
        $deal= $discussion->commentable()->first();
        broadcast(new NewNotification('delete a comment', 'on a deal',$deal))->toOthers();
    }

    /**
     * Handle the Discussion "restored" event.
     *
     * @param  \App\Models\CRM\Discussion\Discussion  $discussion
     * @return void
     */
    public function restored(Discussion $discussion)
    {
        //
    }

    /**
     * Handle the Discussion "force deleted" event.
     *
     * @param  \App\Models\CRM\Discussion\Discussion  $discussion
     * @return void
     */
    public function forceDeleted(Discussion $discussion)
    {
        //
    }
}
