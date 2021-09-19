<?php

namespace App\Models\CRM\Import;

use App\Models\CRM\Pipeline\Pipeline;
use App\Models\CRM\Stage\DefaultStage;
use App\Models\CRM\Stage\Stage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PipelineImport implements ToCollection,
    WithHeadingRow,
    WithBatchInserts,
    WithChunkReading,
    SkipsOnError,
    WithValidation
{
    use Importable, SkipsErrors;


    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $pipeline = Pipeline::create([
                'name' => $row['name'],
            ]);
            $defaultStages = DefaultStage::get();
            foreach ($defaultStages as $stage){
                Stage::create([
                    'name' => $stage->name,
                    'probability' => $stage->probability,
                    'pipeline_id' => $pipeline->id,
                    'priority' => $stage->priority,
                ]);
            }

        }
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required']
        ];
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
