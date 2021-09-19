<?php

namespace App\Models\CRM\Traits;

use App\Models\CRM\Tag\Tag;

trait TagRelationshipTrait
{
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
