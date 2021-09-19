<?php

namespace App\Models\CRM\Template;

use App\Models\Core\Auth\User;
use App\Models\Core\BaseModel;
use App\Models\Core\Traits\BootTrait;
use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Core\Traits\DescriptionGeneratorTrait;

class Template extends BaseModel
{
    use CreatedByRelationship, DescriptionGeneratorTrait;

    protected $table = 'templates';

    protected $fillable = [
        'subject', 'custom_content', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'subject', 'createdBy'
    ];

    use BootTrait {
        boot as public traitBoot;
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function createdRules()
    {
        return [
            'subject' => 'required',
        ];
    }

    public static function boot()
    {
        self::traitBoot();
    }
}
