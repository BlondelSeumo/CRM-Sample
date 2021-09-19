<?php

use App\Models\Core\Auth\Type;
use App\Models\Core\Setting\NotificationEvent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRowInsertToNotificationEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            $notEvents = NotificationEvent::pluck('name')->toArray();
            $checkEvent = array_search('deal_assigned', $notEvents);
            if ($checkEvent == false) {
                $events = [
                    'name' => 'deal_assigned',
                    'type_id' => Type::findByAlias('app')->id,
                ];
                $event = NotificationEvent::query()->insertGetId($events);

                $template = DB::table('notification_templates')->insertGetId(
                    [
                        'subject' => 'A deal named {deal_name} has been assigned by {action_by}',
                        'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}, </span><br></p><p>
We are going to inform you that a deal named  {deal_name}.  has been assigned by {action_by}.</p><br>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>',
                        'custom_content' => null,
                        'type' => 'mail'
                    ]
                );


                DB::table('notification_event_template')->insert([
                    'notification_event_id' => $event,
                    'notification_template_id' => $template,
                ]);
            }
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
