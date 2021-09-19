<?php

namespace App\Models\CRM\Contact;

use App\Models\Core\BaseModel;
use App\Models\Core\Traits\DescriptionGeneratorTrait;
use App\Models\CRM\Contact\Traits\Rules\ContactTypeRules;


class ContactType extends BaseModel
{
    use ContactTypeRules, DescriptionGeneratorTrait;

    protected $fillable = ['name', 'class'];

    protected static $logAttributes = [
        'name', 'class'
    ];
}
