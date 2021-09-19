<?php
namespace Database\Seeders\CRM\Activity;

use App\Models\CRM\Activity\ActivityType;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class ActivityTypesTableSeeder extends Seeder
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
        ActivityType::query()->truncate();

        ActivityType::query()->insert(
            config('crm-config.activity_types')
        );

        $this->enableForeignKeys();
    }
}
