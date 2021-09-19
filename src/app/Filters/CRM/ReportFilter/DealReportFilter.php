<?php

namespace App\Filters\CRM\ReportFilter;

use App\Filters\CRM\Traits\DateFilterTrait;
use App\Filters\CRM\Traits\PipelineFilterTrait;
use App\Filters\CRM\Traits\StatusFilterTrait;
use App\Filters\CRM\Traits\OwnerFilterTrait;
use App\Filters\FilterBuilder;

class DealReportFilter extends FilterBuilder
{
    use StatusFilterTrait, PipelineFilterTrait, DateFilterTrait,OwnerFilterTrait;
}

