<?php


namespace App\Services\CRM\Deal;


use App\Models\CRM\Deal\LostReason;
use App\Services\ApplicationBaseService;

class LostReasonService extends ApplicationBaseService
{
    public function __construct(LostReason $lostReason)
    {
        $this->model = $lostReason;
    }
}
