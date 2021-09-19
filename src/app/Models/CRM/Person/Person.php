<?php

namespace App\Models\CRM\Person;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\BootTrait;
use App\Models\CRM\Contact\ContactType;
use App\Models\CRM\Country\Country;
use App\Models\CRM\Person\Traits\PersonRules;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\CRM\Person\Traits\PersonsRelationship;

class Person extends BaseModel
{
    use BootTrait {
        boot as traitBoot;
    }
    use PersonsRelationship,
        PersonRules,
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
        'owner_id',
        'created_by',
        'attach_login_user_id'
    ];

    protected static $logAttributes = [
        'name', 'address', 'contactType', 'owner',
    ];

    protected $appends = [
        // 'open_deals',               // See OpenClosedDealsTrait
        // 'closed_deals',             // See OpenClosedDealsTrait
        // 'total_deals',              // See OpenClosedDealsTrait
        // 'total_organizations',      // See PersonsRelationship
        // 'total_followers',           // See PersonsRelationship
    ];

    public function contactType()
    {
        return $this->belongsTo(ContactType::class, 'contact_type_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public static function boot()
    {
        parent::boot();

        self::traitBoot();

        static::deleting(function (self $person) {
            $person->activity()->delete();
        });
    }
}
