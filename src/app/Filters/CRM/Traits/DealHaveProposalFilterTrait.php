<?php


namespace App\Filters\CRM\Traits;
use Illuminate\Database\Eloquent\Builder;

trait DealHaveProposalFilterTrait
{
    public function haveProposal($proposal = null)
    {
        if ($proposal == 1)
           return $this->builder->when($proposal, function (Builder $query) use ($proposal) {
                $query->whereNotNull('deal_id');
            });
           return $this->builder->when($proposal, function (Builder $query) use ($proposal) {
                $query->whereNull('deal_id');
            });
    }
}
