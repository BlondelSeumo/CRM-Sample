<?php


    namespace App\Services\CRM\Deal;


    use App\Models\CRM\Deal\Deal;
    use App\Services\ApplicationBaseService;
    use App\Services\CRM\Settings\CrmCustomFiledService;
    use App\Services\CRM\Traits\ExportCustomFieldTrait;
    use Maatwebsite\Excel\Facades\Excel;

    class DealExportService extends ApplicationBaseService
    {
        use ExportCustomFieldTrait;

        public function __construct(Deal $deal)
        {
            $this->model = $deal;
        }

        public function downloadDeal($batch = 0)
        {

            $export_count = config('excel.exports.chunk_size');
            $skip = ($export_count * $batch) - $export_count;
            $data = $this->mapper();
            $relation = [
                'owner:id,email',
                'pipeline:id,name',
                'status:id,name',
                'contextable'
            ];
            $organizations = getChunk($skip, $export_count, $this->model, $data, $relation);
            $title = __t('deal');
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
            $customFields = resolve(CrmCustomFiledService::class)->customFieldsContext('deal')->toArray();
            return array_merge(
                [
                    'Title',
                    'Value',
                    'Pipeline Name',
                    'Status',
                    'Owner Email',
                    'Lead',
                    'Expired at',
                    'Created at',
                    'Updated at'
                ], $customFields);
        }

        private function mapper()
        {
            return fn($deal) => [
                'title' => $deal->title,
                'value' => $deal->value,
                'pipeline_id' => $deal->pipeline ? $deal->pipeline->name : '',
                'status' => $deal->status->name,
                'owner_id' => $deal->owner->email,
                'contextable' => $deal->contextable ? $deal->contextable->name : '',
                'expired_at' => $deal->expired_at,
                'created_at' => $deal->created_at,
                'updated_at' => $deal->updated_at,
                ...$this->formatCustomData($deal->customFields, $contextType = 'deal'),
            ];
        }

    }
