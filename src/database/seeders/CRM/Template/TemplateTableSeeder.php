<?php
namespace Database\Seeders\CRM\Template;

use App\Models\CRM\Template\Template;
use App\Models\CRM\User\User;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;
class TemplateTableSeeder extends Seeder
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
        Template::truncate();

        Template::query()->insert([
            [
                'created_at' => now(),
                'created_by' => array_rand(User::query()->pluck('id')->toArray()),
                'subject' => 'Get 50% OFF!!!',
                'default_content' => file_get_contents(database_path('factories/Templates/Get_50%_OFF.html'))
            ],
            [
                'created_at' => now(),
                'created_by' => array_rand(User::query()->pluck('id')->toArray()),
                'subject' => 'Its Your Birthday',
                'default_content' => file_get_contents(database_path('factories/templates/its_your_birthday.html'))
            ],
            [
                'created_at' => now(),
                'created_by' => array_rand(User::query()->pluck('id')->toArray()),
                'subject' => 'Photography Advertisement',
                'default_content' => file_get_contents(database_path('factories/templates/photography_advertisement.html'))
            ],
            [
                'created_at' => now(),
                'created_by' => array_rand(User::query()->pluck('id')->toArray()),
                'subject' => 'We miss you',
                'default_content' => file_get_contents(database_path('factories/templates/we_miss_you.html'))
            ],
        ]);
        $this->enableForeignKeys();
    }
}
