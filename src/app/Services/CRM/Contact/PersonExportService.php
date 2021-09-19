<?php


    namespace App\Services\CRM\Contact;


    use App\Models\CRM\Person\Person;
    use App\Services\ApplicationBaseService;
    use App\Services\CRM\Settings\CrmCustomFiledService;
    use Maatwebsite\Excel\Facades\Excel;
    use App\Services\CRM\Traits\ExportCustomFieldTrait;

    class PersonExportService extends ApplicationBaseService
    {
        use ExportCustomFieldTrait;

        public function __construct(Person $person)
        {
            $this->model = $person;
        }

        public function downloadPerson($batch = 0)
        {
            $export_count = config('excel.exports.chunk_size');
            $skip = ($export_count * $batch) - $export_count;
            $data = $this->mapper();
            $relation = [];
            $organizations = getChunk($skip, $export_count, $this->model, $data, $relation);
            $title = __t('person');
            return Excel::download(exportBuilder
            (
                $organizations,
                $this->getHeadings(),
                $title
            ), "$title-$batch.xlsx"
            );
        }

        private function getHeadings()
        {
            $customFields = resolve(CrmCustomFiledService::class)->customFieldsContext('person')->toArray();
            return array_merge(
                [
                    'Name',
                    'Phone',
                    'Email',
                    'Lead Group',
                    'Owner Email',
                    'Address',
                    'Country',
                    'Area',
                    'State',
                    'City',
                    'Zip code',
                ], $customFields);
        }

        private function mapper()
        {
            return fn($person) => [

                'name' => $person->name,
                'phone' => $this->phoneFormate($person),
                'email' => $this->emailFormate($person),
                'contact_type_id' => $person->contactType->name,
                'owner_id' => $person->owner->full_name,
                'address' => $person->address,
                'country_id' => $person->country ? $person->country->name : '',
                'area' => $person->area,
                'state' => $person->state,
                'city' => $person->city,
                'zip_code' => $person->zip_code,
                ...$this->formatCustomData($person->customFields, $contextType = 'person'),
            ];
        }

        private function phoneFormate($person)
        {
            $phone = $person->phone->map(function ($item) {
                return $item->value;
            })->toArray();
            return implode(',', $phone);
        }

        public function emailFormate($person)
        {
            $email = $person->email->map(function ($item) {
                return $item->value;
            })->toArray();
            return implode(',', $email);
        }

    }
