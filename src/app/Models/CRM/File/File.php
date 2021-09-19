<?php

namespace App\Models\CRM\File;

use App\Models\Core\File\File as CoreFile;

class File extends CoreFile
{
    protected $appends = ['icon'];
    public function getIconAttribute()
    {
            return config('crm-config.icon.for_file_icon');

    }
}