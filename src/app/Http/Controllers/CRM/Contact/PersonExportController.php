<?php

    namespace App\Http\Controllers\CRM\Contact;

    use App\Http\Controllers\Controller;
    use App\Services\CRM\Contact\PersonExportService;

    class PersonExportController extends Controller
    {
        public function __construct(PersonExportService $service)
        {
            $this->service = $service;
        }

        public function export()
        {
            $count = $this->service->count();
            $export_count = config('excel.exports.chunk_size');
            if ($count >= $export_count) {
                return view('crm.export.export', [
                    'item_per_sheet' => $export_count,
                    'total_sheet_number' => $count / $export_count,
                    'url' => 'export/person',
                    'title' => __t('download_person_data')
                ]);
            }
            return $this->download(0);
        }

        public function download($skip = 0)
        {
            return $this->service->downloadPerson($skip);
        }
    }
