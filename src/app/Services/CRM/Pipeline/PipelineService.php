<?php

namespace App\Services\CRM\Pipeline;

use App\Models\CRM\Person\Person;
use App\Models\CRM\Pipeline\Pipeline;
use App\Services\ApplicationBaseService;
use Illuminate\Database\Eloquent\Builder;

class PipelineService extends ApplicationBaseService
{
    public function __construct(Pipeline $pipeline)
    {
        $this->model = $pipeline;
    }

    public function clientPipelines()
    {
        $personId = Person::where('attach_login_user_id', auth()->user()->id)->first()->id;

        return $this->model::withCount(['stage', 'deals' => function($query) use($personId) {
            $query->whereHas('contactPerson', function (Builder $query) use ($personId){
                $query->where('person_id', '=', $personId);
            });
        }]);
    }

    public function pipelines()
    {
        return $this->model::withCount(['stage', 'deals' => function($query){
            $query->when(!auth()->user()->can('manage_public_access'), function ($query) {
                $query->where('created_by', auth()->user()->id);
            });
        }]);
    }
}
