<?php


namespace App\Services\CRM\Discussion;


use App\Models\CRM\Discussion\Discussion;
use App\Services\ApplicationBaseService;

class DiscussionService extends ApplicationBaseService
{
    public function __construct(Discussion $discussion)
    {
        $this->model = $discussion;
    }
}
