<?php


namespace App\Services\CRM\Settings;


use App\Services\Core\Builder\Form\CustomFieldService;

class CrmCustomFiledService extends CustomFieldService
{

    public function customFieldsContext($context = 'deal')
    {
        return $this->model->where('context', $context)->pluck('name');
    }
}