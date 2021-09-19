<?php
namespace Database\Seeders\CRM\Stage;

use App\Models\CRM\Stage\Stage;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\ConsoleMessageTrait;

class DemoStagesTableSeeder extends Seeder
{
    use ConsoleMessageTrait;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Stage::truncate();
        $this->truncateMessage(new Stage());

        Stage::query()->insert(
            config('crm-config.demo_stages')
        );

        $this->seededMessage(new Stage());
    }
}
