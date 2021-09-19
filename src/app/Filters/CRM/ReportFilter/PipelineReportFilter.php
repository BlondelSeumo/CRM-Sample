<?php

namespace App\Filters\CRM\ReportFilter;


use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class PipelineReportFilter  extends FilterBuilder
{
    public function pipeline($id = null)
    {
        return $this->builder->where('id', $id);
    }

    public function date($date = null)
    {
        $date = json_decode(htmlspecialchars_decode($date), true);
        $this->builder->when($date, function (Builder $builder) use ($date){
            $builder->whereBetween(\DB::raw('DATE(created_at)'), array_values($date));
        });
    }

}