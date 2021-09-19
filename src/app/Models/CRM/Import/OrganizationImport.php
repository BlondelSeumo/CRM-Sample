<?php

namespace App\Models\CRM\Import;

use App\Models\CRM\Contact\ContactType;
use App\Models\CRM\Country\Country;
use App\Models\CRM\Import\Traits\CustomFiledStoreTraits;
use App\Models\CRM\Import\Traits\CustomFiledValidationTraits;
use App\Models\CRM\Organization\Organization;
use App\Models\CRM\User\User;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class OrganizationImport implements
    ToModel,
    WithHeadingRow,
    WithBatchInserts,
    WithChunkReading,
    WithValidation,
    SkipsOnFailure

{
    use Importable, SkipsFailures, CustomFiledStoreTraits, CustomFiledValidationTraits;

    public function model(array $row)
    {
        $created_by = User::where('email', trim($row['created_by_email']))->first();
        $owner = User::where('email', trim($row['owner_email']))->first();
        $contact_type = ContactType::where('name', trim($row['lead_group']))->first();
        $country = Country::where('name', trim($row['country']))->first();

        $organization = Organization::create([
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

        //custom field data creation
        $this->customFiledStore($context = 'organization', $row, $organization);
    }

    public $requiredHeading = [
        "name",
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

    public function rules(): array
    {
        $rules = [
            '*.name' => ['required'],
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

        return $this->customFiledValidation($context = 'organization', $rules);
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
}
