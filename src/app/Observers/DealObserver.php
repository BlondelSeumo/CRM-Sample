<?php

namespace App\Observers;

use App\Models\CRM\Deal\Deal;
use App\Events\NewNotification;

class DealObserver
{
    /**
     * Handle the Deal "created" event.
     *
     * @param  \App\Models\CRM\Deal\Deal  $deal
     * @return void
     */
    public function created(Deal $deal)
    {
        //
        // logger('updated-'.$deal->stage_id);
        broadcast(new NewNotification('Created', 'deal', $deal))->toOthers();
    }

    public function updating(Deal $deal)
    {
        //
        // logger('updating-'.$deal->stage_id);
    }

    /**
     * Handle the Deal "updated" event.
     *
     * @param  \App\Models\CRM\Deal\Deal  $deal
     * @return void
     */
    public function updated(Deal $deal)
    {
        //
        // logger('updated-'.$deal->stage_id);
        broadcast(new NewNotification('Updated', 'deal', $deal))->toOthers();
    }

    public function saving(Deal $deal)
    {
        //
        // logger('saving-'.$deal->stage_id);
    }

    public function saved(Deal $deal)
    {
        //
        // logger('saved-'.$deal->stage_id);
    }

    /**
     * Handle the Deal "deleted" event.
     *
     * @param  \App\Models\CRM\Deal\Deal  $deal
     * @return void
     */
    public function deleted(Deal $deal)
    {
        //
        // logger('updated-'.$deal->stage_id);
        // broadcast(new NewNotification('Deleted', 'deal', $deal))->toOthers();
    }

    /**
     * Handle the Deal "restored" event.
     *
     * @param  \App\Models\CRM\Deal\Deal  $deal
     * @return void
     */
    public function restored(Deal $deal)
    {
        //
    }

    /**
     * Handle the Deal "force deleted" event.
     *
     * @param  \App\Models\CRM\Deal\Deal  $deal
     * @return void
     */
    public function forceDeleted(Deal $deal)
    {
        //
    }
}
