<?php


namespace App\Models\CRM\Import\Traits;


use App\Models\Core\Builder\Form\CustomField;
use App\Services\CRM\Settings\CrmCustomFiledService;

trait CustomFiledValidationTraits
{

	public function customFiledValidation($context, $rules)
	{
		$customFields = resolve(CrmCustomFiledService::class)->customFieldsContext($context)->toArray();

		foreach ($customFields as $field) {
			$customField = CustomField::where('context', $context)
				->where('name', $field)->first();

			$field = str_replace(' ', '_', strtolower($field));
			//select, radio
			if ($customField->meta) {
				$rules["*.{$field}"] = ['nullable',
					function ($attribute, $value, $fail) use ($customField) {
						$values = explode(',', $customField->meta);
						if (!in_array($value, $values)) {
							$fail('The ' . $attribute . ' is not valid.');
						}
					}
				];
			}
			// date
			if ($customField->custom_field_type_id == 5) {
				$rules["*.{$field}"] = ['nullable', 'date'];
			}
			//number
			if ($customField->custom_field_type_id == 6) {
				$rules["*.{$field}"] = ['nullable', 'integer'];
			}
		}
		return $rules;
	}
}
