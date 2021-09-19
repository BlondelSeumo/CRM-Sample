<?php
namespace Database\Seeders\CRM\Deal;

use App\Models\CRM\Deal\LostReason;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class LostReasonsTableSeeder extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        LostReason::query()->truncate();

        LostReason::query()->insert(
            config('crm-config.lost_reasons')
        );

        $this->enableForeignKeys();
    }
}
