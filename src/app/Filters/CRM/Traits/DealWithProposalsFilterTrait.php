<?php

namespace App\Filters\CRM\Traits;

use Illuminate\Database\Eloquent\Builder;

trait DealWithProposalsFilterTrait
{
    public function dealWithProposal($proposal = null)
    {
        if (!$proposal) {
            return $this->builder;
        }

        if ($proposal == 1) {
            $this->builder->where(function (Builder $query) {
                $query->has('proposals');
            });
        } else {
            $this->builder->where(function (Builder $query) {
                $query->doesntHave('proposals');
            });
        }
    }
}
