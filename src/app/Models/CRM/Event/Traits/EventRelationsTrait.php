<?php

namespace App\Models\CRM\Event\Traits;

use App\Models\Core\Traits\CreatedByRelationship;
use App\Models\Core\Traits\StatusRelationship;

trait EventRelationsTrait
{
    use CreatedByRelationship,
        StatusRelationship;
}
