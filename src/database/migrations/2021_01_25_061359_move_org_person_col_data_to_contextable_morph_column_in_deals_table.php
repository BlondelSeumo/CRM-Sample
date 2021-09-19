<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CRM\Deal\Deal;

class MoveOrgPersonColDataToContextableMorphColumnInDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // present data of org_id and person_id move to new contextable col
        // where org not null, deal are count as org's deal
        $orgDeals = Deal::where('organization_id', '!=', null)->get();
        // dd($orgDeals);

        try {
            foreach ($orgDeals as $orgDeal) {
                $orgDeal->update([
                    'contextable_type' => "App\Models\CRM\Organization\Organization",
                    'contextable_id' => $orgDeal->organization_id,
                ]);
            }
            // where org null, deal are count as person's deal
            $personDeals = Deal::where('organization_id', '=', null)
                ->where('person_id', '!=', null)
                ->get();
            foreach ($personDeals as $personDeal) {
                $personDeal->update([
                    'contextable_type' => 'App\Models\CRM\Person\Person',
                    'contextable_id' => $personDeal->person_id,
                ]);
            }
        } catch (\Exception $e) {
            throw $e->getMessage();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
