<?php

namespace App\Models\CRM\Deal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Core\BaseModel;
use App\Models\Core\File\File;
use App\Models\CRM\Organization\Organization;
use App\Models\CRM\Deal\Traits\DealRulesTrait;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\CRM\Deal\Traits\DealRelationshipTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Deal extends BaseModel
{
    use DealRelationshipTrait,              // All Relations are defined here
        DealRulesTrait,                     // Validation Rules
        DescriptionGeneratorTrait,
        HasFactory,
        RevisionableTrait
        ;

        protected $revisionEnabled = true;
        protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
        protected $historyLimit = 20; //Maintain a maximum of 500 changes at any point of time, while cleaning up old revisions.
        protected $keepRevisionOf = [
            'value',
            'pipeline_id',
            'stage_id',
            'contextable_type',
            'contextable_id',
            'lost_reason_id',
            'status_id',
            'owner_id',
            'expired_at',
        ];

        protected $fillable = [
        'title',
        'description',
        'value',
        'pipeline_id',
        'stage_id',
        'lost_reason_id',
        'status_id',
        'created_by',
        'owner_id',
        'contextable_type',
        'contextable_id',
        'expired_at',
        'histories',
        'comment',
    ];

    protected $casts = [
        'pipeline_id' => 'int',
        'stage_id' => 'int',
        'lost_reason_id' => 'int',
        'status_id' => 'int',
        'owner_id' => 'int',
        'person_id' => 'int',
        'organization_id' => 'int',
        'value' => 'int',
    ];

    protected $appends = [
        // 'total_followers',          // Returning total followers for Deal Details page
        // 'total_participants',       // Returning total participants for Deal Details page
        // 'next_activity',            // Returning next activity
        'avg_age_of_deal'
    ];

    protected static $logAttributes = [
        'title', 'value', 'pipeline', 'stage', 'status.name', 'person', 'organization', 'owner',
    ];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_id');
    }

    public function fileUpload()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public static function boot()
    {
        parent::boot();

        if (!app()->runningInConsole()){
            static::creating(function($model){
                return $model->fill([
                    'created_by' => $model->created_by ?: auth()->id()
                ]);
            });
        }

        static::deleting(function (self $deal) {
            $deal->activity()->delete();
        });
    }
}
