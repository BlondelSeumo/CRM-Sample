<?php


namespace App\Models\CRM\Traits;


use App\Models\Core\Builder\Form\CustomFieldValue;

trait CustomFieldRelationshipTrait
{
    public function customFields()
    {
        return $this->morphMany(CustomFieldValue::class, 'contextable');
    }
}