<?php
namespace Database\Seeders\CRM\Contact;

use App\Models\CRM\Contact\PhoneEmailType;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;
class PhoneEmailTypeSeeder extends Seeder
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
        PhoneEmailType::query()->truncate();

        PhoneEmailType::query()->insert(
            config('crm-config.phone_email_types')
        );

        $this->enableForeignKeys();
    }
}
