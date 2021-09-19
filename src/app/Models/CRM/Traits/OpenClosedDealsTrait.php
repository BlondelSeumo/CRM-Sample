<?php

namespace App\Models\CRM\Traits;

use App\Models\Core\Status;

/**
 * Trait OpenClosedDealsTrait
 * @package App\Models\CRM\Traits
 */
trait OpenClosedDealsTrait
{
    /**
     * If you are using this trait then please make sure
     * that you are already declared the appends property in your model
     * with 'open_deals' and 'closed_deals' attributes
     * Note that attribute names are typically referenced in "snake case",
     * even though the accessor is defined using "camel case"
     * @return int
     */
    protected function getOpenDealsAttribute()
    {
        $status = Status::findByNameAndType('status_open', 'deal')->id ?? null;

        return $this->deals()
            ->where('status_id', $status)
            ->count();
    }

    protected function getClosedDealsAttribute()
    {
        $status = Status::findByNameAndType('status_open', 'deal')->id ?? null;

        return $this->deals()
            ->whereNotIn('status_id', [$status])
            ->count();
    }

    protected function getTotalDealsAttribute()
    {
        return $this->deals()->count();
    }
}
