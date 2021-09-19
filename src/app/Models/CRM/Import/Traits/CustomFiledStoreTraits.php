<?php


    namespace App\Models\CRM\Import\Traits;


    use App\Models\Core\Builder\Form\CustomField;
    use App\Services\CRM\Settings\CrmCustomFiledService;

    trait CustomFiledStoreTraits
    {
        public function customFiledStore($context, $row, $model)
        {
            $customFields = resolve(CrmCustomFiledService::class)->customFieldsContext($context)->toArray();
            foreach ($customFields as $key) {
                $arrayKey = str_replace(' ', '_', strtolower($key));
                // request has custom data insertion
                if (array_key_exists($arrayKey, $row)) {
                    $customFieldId = CustomField::where('context', $context)
                        ->where('name', $key)->first()->id;

                    if ($row[$arrayKey]) {
                        $array = [
                            'value' => $row[$arrayKey],
                            'custom_field_id' => $customFieldId
                        ];
                        $model->customFields()->create($array);

                    }
                }
            }
        }
    }
