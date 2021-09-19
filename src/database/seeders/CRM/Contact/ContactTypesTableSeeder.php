<?php
namespace Database\Seeders\CRM\Contact;

use App\Models\CRM\Contact\ContactType;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class ContactTypesTableSeeder extends Seeder
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
        ContactType::query()->truncate();

        ContactType::query()->insert(
            config('crm-config.contact_types')
        );

        $this->enableForeignKeys();
    }
}
