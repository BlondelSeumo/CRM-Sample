<?php

namespace App\Http\Controllers\CRM\Deal;

use App\Http\Controllers\Controller;
use App\Services\CRM\Deal\DealExportService;
use Illuminate\Http\Request;

class DealExportController extends Controller
{
    public function __construct(DealExportService $service)
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
                'url' => 'export/deal',
                'title' => __t('download_deal_data')
            ]);
        }
        return $this->download(0);
    }

    public function download($skip = 0)
    {
        return $this->service->downloadDeal($skip);
    }
}

