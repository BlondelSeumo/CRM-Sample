<?php

namespace App\Models\CRM\Proposal;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Proposal\Traits\ProposalRelationshipsTrait;
use App\Models\CRM\Proposal\Traits\ProposalRules;

class Proposal extends BaseModel
{
    use ProposalRelationshipsTrait,
        DescriptionGeneratorTrait,
        ProposalRules;

    protected $fillable = ['subject', 'content', 'status_id', 'deal_id', 'owner_id', 'created_by', 'sent_at', 'accepted_at', 'expired_at'];

    protected static $logAttributes = ['subject', 'deal', 'owner', 'status', 'sent_at', 'accepted_at'];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    use BootTrait {
        boot as public traitBoot;
    }

    public static function boot()
    {
        self::traitBoot();

        if (!app()->runningInConsole()) {
            static::saving(function ($model) {
                $model->owner_id = $model->owner_id ?: auth()->id();
            });
        }

    }

    public function scopeHasNoDeal($query)
    {
        $query->where('deal_id', null);
    }
}
