<?php

namespace Database\Seeders\Status;

use App\Models\Core\Status;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

class StatusSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeders.
     */
    public function run()
    {
        $this->disableForeignKeys();
        Status::query()->truncate();
        $statuses = [
            [
                'name' => 'status_active',
                'type' => 'user',
                'class' => 'success',
            ],
            [
                'name' => 'status_inactive',
                'type' => 'user',
                'class' => 'danger',
            ],
            [
                'name' => 'status_invited',
                'type' => 'user',
                'class' => 'purple',
            ],
            [
                'name' => 'status_open',
                'type' => 'crm',
                'class' => 'success',
            ],
            [
                'name' => 'status_closed',
                'type' => 'crm',
                'class' => 'danger',
            ],
            [
                'name' => 'status_draft',
                'type' => 'proposal',
                'class' => 'warning',
            ],
            [
                'name' => 'status_sent',
                'type' => 'proposal',
                'class' => 'primary',
            ],
            [
                'name' => 'status_accepted',
                'type' => 'proposal',
                'class' => 'success',
            ],
            [
                'name' => 'status_rejected',
                'type' => 'proposal',
                'class' => 'danger',
            ],
            [
                'name' => 'status_no_reply',
                'type' => 'proposal',
                'class' => 'info',
            ],
            [
                'name' => 'status_todo',
                'type' => 'activity',
                'class' => 'primary',
            ],
            [
                'name' => 'status_done',
                'type' => 'activity',
                'class' => 'success',
            ],
            [
                'name' => 'status_open',
                'type' => 'deal',
                'class' => 'primary',
            ],
            [
                'name' => 'status_won',
                'type' => 'deal',
                'class' => 'success',
            ],
            [
                'name' => 'status_lost',
                'type' => 'deal',
                'class' => 'danger',
            ],
        ];

        Status::query()->insert($statuses);

        $this->enableForeignKeys();
    }
}
