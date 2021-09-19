<?php

namespace App\Models\CRM\Organization;

use App\Models\Core\BaseModel;
use App\Models\CRM\Country\Country;
use App\Models\CRM\File\File;
use App\Models\CRM\Note\Note;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\CRM\Organization\Traits\OrganizationRelationshipsTrait;
use App\Models\CRM\Organization\Traits\Rules\OrganizationRules;
use App\Models\CRM\Traits\OpenClosedDealsTrait;

class Organization extends BaseModel
{
    use BootTrait {
        boot as traitBoot;
    }
    use OrganizationRelationshipsTrait,
        OrganizationRules,
        // OpenClosedDealsTrait,
        DescriptionGeneratorTrait;


    protected $fillable = [
        'name',
        'address',
        'country_id',
        'city',
        'state',
        'zip_code',
        'area',
        'contact_type_id',
        'created_by',
        'owner_id'
    ];

    protected static $logAttributes = [
        'name', 'address', 'contactType', 'owner'
    ];

    protected $appends = [
        // 'open_deals',               // See OpenClosedDealsTrait
        // 'closed_deals',             // See OpenClosedDealsTrait
        // 'total_deals',              // See OpenClosedDealsTrait
        // 'total_peoples',            // See OrganizationRelationshipsTrait
        // 'total_followers'           // See OrganizationRelationshipsTrait
    ];

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public static function boot()
    {
        parent::boot();

        self::traitBoot();

        static::deleting(function (self $organization) {
            $organization->activity()->delete();
        });
    }
}
