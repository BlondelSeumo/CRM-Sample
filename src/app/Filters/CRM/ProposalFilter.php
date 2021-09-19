<?php

namespace App\Filters\CRM;

use App\Filters\CRM\Traits\PublicAccessFilterTrait;
use App\Models\Core\Status;
use App\Filters\FilterBuilder;
use App\Models\CRM\Person\Person;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\CRM\Traits\DateFilterTrait;
use App\Filters\CRM\Traits\TagsFilterTrait;
use App\Filters\Core\traits\CreatedByFilter;
use App\Filters\CRM\Traits\OwnerFilterTrait;
use App\Filters\CRM\Traits\StatusFilterTrait;
use App\Filters\CRM\Traits\ContactTypeFilterTrait;

class ProposalFilter extends FilterBuilder
{
    use CreatedByFilter,
        OwnerFilterTrait,
        TagsFilterTrait,
        DateFilterTrait,
        StatusFilterTrait;

    public function clientRoleAccess($value = 'no')
    {
        $personId = Person::where('attach_login_user_id', auth()->user()->id)->first()->id;
        $this->builder->when($value == 'client', function (Builder $query) use ($personId) {
            $query->whereHas('deal', function (Builder $query) use ($personId) {
                $query->whereHas('contactPerson', function (Builder $query) use ($personId) {
                    $query->where('person_id', '=', $personId);
                });
            });
        });
    }

    public function createdAt()
    {
        $date = request()->created_at;
        $this->builder->when($date, function (Builder $builder) use ($date) {
            $builder->whereBetween(\DB::raw('DATE(created_at)'), array_values($date));
        });
    }

    public function proposalWithDeal($deal = null)
    {
        if (!$deal) {
            return $this->builder;
        }
        if ($deal == 1) {
            $this->builder->where(function (Builder $query) {
                $query->has('deal');
            });
        } else {
            $this->builder->where(function (Builder $query) {
                $query->doesntHave('deal');
            });
        }
    }

    public function dealValue($value = null)
    {
        $value = json_decode(htmlspecialchars_decode($value), true);
        return $this->builder->when($value, function (Builder $builder) use ($value) {
            $builder->whereHas('deal', function (Builder $builder) use ($value) {
                $builder->whereBetween('value', array_values($value));
            });
        });
    }

    public function search($search = null)
    {
        return $this->builder->when($search, function (Builder $builder) use ($search) {
            $builder->where('subject', 'LIKE', "%$search%");
        });
    }

    public function dealStrategy($proposal_status)
    {
        $sent_status_id = Status::findByNameAndType('status_sent', 'proposal')->id;
        $draft_status_id = Status::findByNameAndType('status_draft', 'proposal')->id;

        return $proposal_status == 1 ? $this->builder->where('status_id', $sent_status_id) : $this->builder->where('status_id', $draft_status_id);
    }

    public function statusId($status_id = null)
    {
        return $status_id ? $this->builder->where('status_id', $status_id) : $this->builder;
    }

    public function pipeline($id = null)
    {
        return $id ? $this->builder->when($id, function (Builder $builder) use ($id) {
            $builder->whereHas('deal', function (Builder $builder) use ($id) {
                $builder->where('pipeline_id', $id);
            });
        }) : $this->builder;
    }

    public function dealStatusId($id = null)
    {
        return $id ? $this->builder->when($id, function (Builder $builder) use ($id) {
            $builder->whereHas('deal', function (Builder $builder) use ($id) {
                $builder->where('status_id', $id);
            });
        }) : $this->builder;
    }

    public function publicRoleAccess($value = 'no')
    {
        $this->builder->when($value == 'no', function (Builder $query) {
            $query->whereHas('CreatedBy', function ($query) {
                $query->where('owner_id', auth()->user()->id);
            });
        });
        $this->builder->when($value == 'client', function (Builder $query) {
            $draft = Status::findByNameAndType('status_draft', 'proposal');
            $query->whereHas('status', function (Builder $query) use ($draft) {
                $query->where('id', '!=', $draft->id);
            });
            $query->whereHas('deal.contactPerson', function (Builder $query) {
                $query->where('person_id', '=', auth()->user()->id);
            });
        });
    }
}
