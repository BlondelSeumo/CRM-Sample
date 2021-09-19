<?php


namespace App\Services\CRM\Stage;


use App\Models\CRM\Stage\Stage;
use App\Services\ApplicationBaseService;

class StageService extends ApplicationBaseService
{
    public function __construct(Stage $stage)
    {
        $this->model = $stage;
    }
}
