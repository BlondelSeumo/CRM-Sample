<?php

    namespace App\Services\CRM\Traits;

    use Illuminate\Database\Eloquent\Model;

    trait personOrganizationDetails
    {
        use PersonOrganizationRelations;

        public function personOrganizationDetails(Model $model, $id, $pivot_data)
        {
            return $model->where('id', $id)
                ->when(!auth()->user()->can('manage_public_access'), function ($query) {
                    $query->where('owner_id', auth()->user()->id);
                })
                ->with($this->relations($pivot_data))
                ->with(['customFields'])
                ->withCount(['openDeals', 'closeDeals'])
                ->withCount('linkedContact')
                ->firstOrFail();
        }
    }
