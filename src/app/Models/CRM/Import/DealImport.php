<?php

    namespace App\Models\CRM\Import;

    use App\Models\Core\Status;
    use App\Models\CRM\Deal\Deal;
    use App\Models\CRM\Import\Traits\CustomFiledStoreTraits;
    use App\Models\CRM\Import\Traits\CustomFiledValidationTraits;
    use App\Models\CRM\Organization\Organization;
    use App\Models\CRM\Person\Person;
    use App\Models\CRM\Pipeline\Pipeline;
    use App\Models\CRM\Stage\Stage;
    use App\Models\CRM\User\User;
    use App\Rules\DealImportContactPersonRule;
    use App\Rules\DealImportLeadRule;
    use Illuminate\Validation\Rule;
    use Maatwebsite\Excel\Concerns\Importable;
    use Maatwebsite\Excel\Concerns\SkipsFailures;
    use Maatwebsite\Excel\Concerns\SkipsOnFailure;
    use Maatwebsite\Excel\Concerns\ToModel;
    use Maatwebsite\Excel\Concerns\WithBatchInserts;
    use Maatwebsite\Excel\Concerns\WithChunkReading;
    use Maatwebsite\Excel\Concerns\WithHeadingRow;
    use Maatwebsite\Excel\Concerns\WithValidation;

    class DealImport implements
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
            // default values
            $pipeline_id = Pipeline::where('name', trim($row['pipeline_name']))->first()->id;

            $first_stage_of_requested_pipeline = Stage::where('pipeline_id', $pipeline_id)
                ->orderBy('priority')
                ->first()
                ->id;

            $owner_id = User::where('email', trim($row['owner_email']))->first();

            //[$prefix, $lead, $contactPerson] = Str::of($row['lead'])->split("/:/");

            $value = explode(":", $row['lead']);
            $prefix = count($value) ? $value[0] : null;
            $lead = count($value) > 1 ? $value[1] : null;
            $contactPerson = count($value) > 2 ? $value[2] : null;

            if (strtolower($prefix) == 'p') {
                $contextable_type = Person::class;
                $contextable_id = Person::where('name', $lead)->first()->id;
                $person_id = $contextable_id;
            } else {
                $contextable_type = Organization::class;
                $contextable_id = Organization::where('name', $lead)->first()->id;
                $person_id = $contactPerson ? Person::where('name', $contactPerson)->first()->id : null;
            }


            $status_id = Status::where('type', 'deal')->where('name', $row['status'])->first()->id;

            $deal = Deal::create([
                'title' => $row['title'],
                'value' => $row['value'] ? $row['value'] : 0,
                'pipeline_id' => $pipeline_id,
                'stage_id' => $first_stage_of_requested_pipeline,
                'status_id' => $status_id,
                'owner_id' => $owner_id ? $owner_id->id : null,
                'contextable_type' => $contextable_type,
                'contextable_id' => $contextable_id,
                'expired_at' => $row['expired_at'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
            ]);

            //Contact person data insert
            $this->contactPersonStore($deal, $person_id);

            //custom field data creation

            $this->customFiledStore($context = 'deal', $row, $deal);
        }

        public $requiredHeading = [
            "title",
            "value",
            "pipeline_name",
            "status",
            "owner_email",
            "lead",
            "expired_at",
            "created_at",
            "updated_at",
        ];

        public function contactPersonStore($deal, $person)
        {
            if ($person) {
                $deal->contactPerson()->sync($person);
            }
        }

        public function rules(): array
        {
            $rules = [
                '*.title' => ['required'],
                '*.pipeline_name' => ['required', 'string', 'exists:pipelines,name'],
                '*.status' => ['required', 'string', 'exists:statuses,name'],
                '*.owner_email' => [
                    'nullable',
                    'email',
                    Rule::exists('users', 'email')
                        ->whereNull('deleted_at')
                ],
                '*.lead' => ['required', 'string', DealImportLeadRule::new(true)],
            ];

            //custom field rules

            return $this->customFiledValidation($context = 'deal', $rules);
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
