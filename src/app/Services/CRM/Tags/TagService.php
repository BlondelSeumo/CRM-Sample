<?php


namespace App\Services\CRM\Tags;


use App\Models\CRM\Tag\Tag;
use App\Services\ApplicationBaseService;

class TagService extends ApplicationBaseService
{
    public function __construct(Tag $tag)
    {
        $this->model = $tag;
    }
}
