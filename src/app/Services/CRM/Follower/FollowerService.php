<?php


namespace App\Services\CRM\Follower;


use App\Models\CRM\Follower\Follower;
use App\Services\ApplicationBaseService;

class FollowerService extends ApplicationBaseService
{
    public function __construct(Follower $follower)
    {
        $this->model = $follower;
    }

}
