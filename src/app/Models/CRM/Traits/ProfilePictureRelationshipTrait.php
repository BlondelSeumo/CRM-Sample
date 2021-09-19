<?php

namespace App\Models\CRM\Traits;

use App\Models\Core\File\File;

trait ProfilePictureRelationshipTrait
{
    public function profilePicture()
    {
        return $this->morphOne(File::class, 'fileable')
            ->where('type','profile_picture');
    }
}
