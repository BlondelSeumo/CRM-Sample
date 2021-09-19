<?php

namespace App\Services\CRM\Traits;

use App\Models\CRM\Deal\Deal;

trait DealHistoriesTrait
{
    public static function getHistories($id)
    {
        $stages = Deal::where('id', $id)->with('dealStage')->first()->dealStage;
        return self::mapStages($stages);
    }

    public static function mapStages($stages)
    {
        return $stages->map(
            function ($item, $key) {
                return [
                    'deal_id' => $item->pivot->deal_id,
                    'stage_id' => $item->pivot->stage_id,
                    'created_at' => $item->pivot->created_at,
                    'updated_at' => $item->pivot->updated_at
                ];
            }
        );
    }

}
