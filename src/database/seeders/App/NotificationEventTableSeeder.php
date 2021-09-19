<?php

namespace Database\Seeders\App;

use App\Models\Core\Auth\Type;
use App\Models\Core\Setting\NotificationEvent;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class NotificationEventTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        $appTypeId = Type::findByAlias('app')->id;
        $events = [
            [
                'name' => 'user_invitation', //keep
                'type_id' => $appTypeId,
            ],
//            [
//                'name' => 'user_invitation_canceled',
//                'type_id' => $appTypeId,
//            ],
//            [
//                'name' => 'user_invited',
//                'type_id' => $appTypeId,
//            ],
            [
                'name' => 'password_reset', //keep
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'user_joined', //keep
                'type_id' => $appTypeId,
            ],

            //role
            [
                'name' => 'roles_created',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'roles_updated',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'roles_deleted',
                'type_id' => $appTypeId,
            ],

            // Pipeline
            [
                'name' => 'pipeline_created',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'pipeline_updated',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'pipeline_deleted',
                'type_id' => $appTypeId,
            ],

            // Deal

            [
                'name' => 'deal_created',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'deal_updated',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'deal_deleted',
                'type_id' => $appTypeId,
            ],
            [
                'name' => 'deal_assigned',
                'type_id' => $appTypeId,
            ],

            // Proposal

//            [
//                'name' => 'proposal_send',
//                'type_id' => $appTypeId,
//            ],
//            [
//                'name' => 'proposal_approved',
//                'type_id' => $appTypeId,
//            ],

        ];
        NotificationEvent::query()->truncate();
        NotificationEvent::query()->insert($events);
        $this->enableForeignKeys();
    }
}
