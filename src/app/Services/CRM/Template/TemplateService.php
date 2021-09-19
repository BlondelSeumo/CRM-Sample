<?php

namespace App\Services\CRM\Template;


use App\Models\CRM\Template\Template;
use App\Services\ApplicationBaseService;

class TemplateService extends ApplicationBaseService
{
    public function __construct(Template $template)
    {
        $this->model = $template;
    }
}