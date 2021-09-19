<?php
namespace Database\Seeders\CRM\Stage;

use App\Models\CRM\Stage\DefaultStage;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class DefaultStagesTableSeeder extends Seeder
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
        DefaultStage::query()->truncate();

        DefaultStage::query()->insert(
            config('crm-config.default_stages')
        );

        $this->enableForeignKeys();
    }
}
