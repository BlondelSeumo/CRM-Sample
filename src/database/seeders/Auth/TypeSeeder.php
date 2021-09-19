<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\Type;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class TypeSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeders.
     */
    public function run()
    {
        $this->disableForeignKeys();
        Type::query()->truncate();
        Type::query()->insert([
            [
                'name' => 'App',
                'alias' => 'app',
            ],
            [
                'name' => 'CRM',
                'alias' => 'crm',
            ],
            [
                'name' => 'Brand',
                'alias' => 'brand',
            ],
        ]);
    }
}
