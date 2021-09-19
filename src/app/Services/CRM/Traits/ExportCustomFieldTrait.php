<?php


	namespace App\Services\CRM\Traits;


	use App\Models\Core\Builder\Form\CustomField;
    use App\Services\CRM\Settings\CrmCustomFiledService;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Support\Carbon;

    trait ExportCustomFieldTrait
	{
        public function formatCustomData(Collection $customFieldValues, $contextType)
        {
            $customFields = resolve(CrmCustomFiledService::class)->customFieldsContext($contextType)->toArray();
            return collect($customFields)
                ->map(function ($key) use ($customFieldValues, $contextType) {
                    $customField = CustomField::with('customFieldType')
                        ->where('context', $contextType)
                        ->where('name', $key)
                        ->first();

                    $customFieldName = $customField->customFieldType->name;
                    $customFieldId = $customField->id;

                    $customFieldValue = $customFieldValues->filter(function ($item, $index) use ($customFieldId){
                        if ($item->custom_field_id == $customFieldId){
                            return $item;
                        }
                    })->first();

                    if ($customFieldValue){
                        if ($customFieldName == 'date')
                        {
                            return Carbon::parse($customFieldValue->value, 'UTC')
                                ->setTimezone(config('settings.application.time_zone'))
                                ->toDateString();
                        }
                        return $customFieldValue->value;
                    }
                })->toArray();
        }
	}
