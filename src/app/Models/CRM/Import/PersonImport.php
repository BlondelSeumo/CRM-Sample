<?php

namespace App\Models\CRM\Import;

use App\Models\CRM\Contact\ContactType;
use App\Models\CRM\Contact\PhoneEmailType;
use App\Models\CRM\Country\Country;
use App\Models\CRM\Import\Traits\CustomFiledStoreTraits;
use App\Models\CRM\Import\Traits\CustomFiledValidationTraits;
use App\Models\CRM\Person\Person;
use App\Models\CRM\User\User;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;


class PersonImport extends DefaultValueBinder implements
    ToModel,
    WithHeadingRow,
    WithBatchInserts,
    WithChunkReading,
    WithValidation,
    SkipsOnFailure,
    WithCustomValueBinder

{
    use Importable, SkipsFailures, CustomFiledStoreTraits, CustomFiledValidationTraits;

    public $requiredHeading = [
        "name",
        "email",
        "phone",
        "lead_group",
        "created_by_email",
        "owner_email",
        "address",
        "country",
        "area",
        "city",
        "state",
        "zip_code"
    ];

    public function model(array $row)
    {
        $created_by = User::where('email', trim($row['created_by_email']))->first();
        $owner = User::where('email', trim($row['owner_email']))->first();
        $contact_type = ContactType::where('name', trim($row['lead_group']))->first();
        $country = Country::where('name', trim($row['country']))->first();

        $person = Person::create([
            'name' => $row['name'],
            'address' => $row['address'],
            'contact_type_id' => $contact_type->id,
            'created_by' => $created_by ? $created_by->id : null,
            'owner_id' => $owner ? $owner->id : null,
            'country_id' => $country ? $country->id : null,
            'area' => $row['area'],
            'city' => $row['city'],
            'state' => $row['state'],
            'zip_code' => $row['zip_code'],
        ]);

        $this->phoneEmailImport($person, $row['phone'], $row['email']);

        //custom field data creation
        $this->customFiledStore($context = 'person', $row, $person);
    }

    public function phoneEmailImport($person, $phones, $emails)
    {
        if ($phones) {
            $phones = explode(',', $phones);
            foreach ($phones as $phone) {
                $phone = explode(':', $phone);
                try {
                    $array = [
                        'value' => $phone[0],
                        'type_id' => count($phone) > 1
                            ?
                            PhoneEmailType::where('name', $phone[1])->first()->id ?? null
                            : null
                    ];
                    $person->phone()->updateOrCreate($array);
                } catch (\Exception $exception) {
                    return $exception;
                }
            }
        }

        if ($emails) {
            $emails = explode(',', $emails);
            foreach ($emails as $email) {
                $email = explode(':', $email);
                try {
                    $array = [
                        'value' => $email[0],
                        'type_id' => count($email) > 1
                            ?
                            PhoneEmailType::where('name', $email[1])->first()->id ?? null
                            : null
                    ];
                    $person->email()->updateOrCreate($array);
                } catch (\Exception $exception) {
                    return $exception;
                }
            }
        }
    }

    public function rules(): array
    {
        $rules = [
            '*.name' => ['required'],
            '*.email' => ['nullable'],
            '*.lead_group' => ['required', 'string', 'exists:contact_types,name'],
            '*.owner_email' => [
                'nullable',
                'email',
                Rule::exists('users', 'email')
                    ->whereNull('deleted_at')
            ],
            '*.created_by_email' => [
                'nullable',
                'email',
                Rule::exists('users', 'email')
                    ->whereNull('deleted_at')
            ],
            '*.country' => ['nullable', 'string', 'exists:countries,name'],
        ];

        //custom field rules
        return $this->customFiledValidation($context = 'person', $rules);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
}
