<?php

namespace Database\Seeders\App;

use App\Models\Core\Notification\NotificationTemplate;
use App\Models\Core\Setting\NotificationEvent;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
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

        NotificationEvent::withoutGlobalScope('name')->get()->map(function (NotificationEvent $event) {
            if ($event->name != 'user_invitation' && $event->name != 'password_reset' && $event->name != 'deal_assigned') {
                [$name, $action] = explode('_', $event->name);
                $templates = [
                    'system' => '',
                    'subject' => '',
                    'content' => ''
                ];
                if (array_key_exists($event->name, $this->template())) {
                    $templates = $this->template()[$event->name];
                } elseif (array_key_exists($action, $this->template())) {
                    $templates = $this->template()[$action];
                }

                $mail = NotificationTemplate::query()->create([
                    'subject' => strtr($templates['subject'], [
                        '{resource}' => $name,
                        '{app_name}' => $event->type->alias == 'app' ? '{app_name}' : '{brand_name}'
                    ]),
                    'default_content' => strtr($templates['content'], [
                        '{resource}' => $name,
                        '{button_label}' => 'View ' . ucfirst($name)
                    ]),
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $database = NotificationTemplate::create([
                    'subject' => null,
                    'default_content' => strtr($templates['system'], [
                        '{resource}' => $name
                    ]),
                    'custom_content' => null,
                    'type' => 'database'
                ]);

                $event->templates()->attach(
                    [$database->id, $mail->id]
                );

            } else if ($event->name == 'user_invitation') {
                $mail = NotificationTemplate::create([
                    'subject' => 'User invitation form {app_name}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}, </span><br></p><p>Hope this mail finds you well and healthy. We are informing you that you\'ve been invited to our application by {action_by}. It\'ll be a great opportunity to work with you.</p><br>
<p><a href="{invitation_url}" target="_blank" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none">Accept Invitation</a></p><br>

<p></p><p>Thanks &amp; Regards,
</p><p>{app_name}</p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            } else if ($event->name == 'password_reset') {
                $mail = NotificationTemplate::create([
                    'subject' => 'Password reset link provided by {app_name}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}, </span><br></p><p>Your request for reset password has been approved from {app_name}. Press the button below to reset the password.</p><br>
<p><a href="{reset_password_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">Reset password</a></p><br>

We are highly expecting you as soon as possible. Hope you\'ll join us.
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            } else if ($event->name == 'deal_assigned') {
                $mail = NotificationTemplate::create([
                    'subject' => 'A deal named {deal_name} has been assigned by {action_by}',
                    'default_content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name}, </span><br></p><p>
We are going to inform you that a deal named  {deal_name}.  has been assigned by {action_by}.</p><br>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>',
                    'custom_content' => null,
                    'type' => 'mail'
                ]);

                $event->templates()->attach(
                    [$mail->id]
                );
            }

        });
        $this->enableForeignKeys();
    }

    public function template()
    {
        return [
            'user_joined' => [
                'system' => 'A new user has been joined in {app_name}',
                'subject' => 'A new user has been joined in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name},</span><br></p><p>It\'s a piece of good news that a new user {name} has been joined in our application. Hope you will enjoy his work and collaborations.</p><br>
<p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">View Resource</a></p><br>

<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'user_invitation' => [
                'system' => '{name} has been invited by {action_by}.',
                'subject' => 'A new user has been invited in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name},</span><br></p><p>It\'s a piece of good news that a new user {name} has been invited in our application, invited by {action_by}.</p><br>
<p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">View Resource</a></p><br>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'created' => [
                'system' => 'A new {resource} named {name} has been created by {action_by}.',
                'subject' => 'A new {resource} has been created in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name},</span><br></p><p>It\'s a piece of good news that a new {resource} named {name} has been created in our application by {action_by}. Please have a look at that.</p><br>
<p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; ; text-decoration: none; text-underline: none" target="_blank">{button_label}</a></p><br>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'updated' => [
                'system' => 'A {resource} named {name} has been updated by {action_by}.',
                'subject' => 'A {resource} has been updated in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name},</span><br></p><p>It\'s a piece of good news that a {resource} named {name} has been updated in our application by {action_by}. Please have a look at that.</p><br>
<p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; text-decoration: none; text-underline: none" target="_blank">{button_label}</a></p><br>

<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],
            'deleted' => [
                'system' => 'A {resource} named {name} has been deleted by {action_by}.',
                'subject' => 'A {resource} has been deleted in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name},</span><br></p><p>We are going to inform you that a {resource} named {name} has been deleted from our application by {action_by}.</p>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],

            // Pipeline

            'pipeline_created' => [
                'system' => 'A new pipeline named {pipeline_name} has been created by {action_by}.',
                'subject' => ' A new pipeline named {pipeline_name} has been created in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name},</span><br></p><p>We are going to inform you that a pipeline named {pipeline_name} has been created in our application by {action_by}. Please have a look at that.</p><br>
<p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; ; text-decoration: none; text-underline: none" target="_blank">View pipeline</a></p><br>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],

            'pipeline_updated' => [
                'system' => 'A new pipeline named {pipeline_name} has been updated by {action_by}.',
                'subject' => 'A new pipeline named {pipeline_name} has been updated in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name},</span><br></p><p> We are going to inform you that a pipeline named {pipeline_name} has been updated by {action_by}. </p><br>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],

            'pipeline_deleted' => [
                'system' => 'A new pipeline named {pipeline_name} has been deleted by {action_by}.',
                'subject' => 'A new pipeline named {pipeline_name} has been deleted in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name},</span><br></p><p>We are going to inform you that a pipeline named {pipeline_name} has been deleted by {action_by}.</p>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],


            // Deal

            'deal_created' => [
                'system' => 'A new deal named {deal_name} has been created by {action_by}.',
                'subject' => 'A new deal named {deal_name} has been created in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name},</span><br></p><p>We are going to inform you that a deal named {deal_name} has been created in {pipeline_name} pipeline by {action_by}. Please have a look at that</p><br>
<p><a href="{resource_url}" style="background: #4466F2;color: white;padding: 9px;border-radius: 4px;cursor: pointer; ; text-decoration: none; text-underline: none" target="_blank"> View deal </a></p><br>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],

            'deal_updated' => [
                'system' => 'A new deal named {deal_name} has been updated by {action_by}.',
                'subject' => 'A new deal named {deal_name} has been updated in {app_name}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name},</span> <br></p><p>We are going to inform you that a deal named {deal_name} has been updated by {action_by}. </p><br>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],

            'deal_deleted' => [
                'system' => 'A deal named {deal_name} has been deleted by {action_by}.',
                'subject' => 'A deal named {deal_name} has been deleted by {action_by}',
                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
<p>
</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name},</span><br></p><p>We are going to inform you that a deal named {deal_name} has been deleted by {action_by}.</p>
<p></p><p>Thanks for being with us.
</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
            ],


            // Proposal Send
//
//            'proposal_send' => [
//                'system' => 'A proposal has been sent in {deal_name} by {action_by}.',
//                'subject' => 'A proposal has been sent in {deal_name} by {action_by}.',
//                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
//<p>
//</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name},</span><br></p><p>It\'s good to announce that a proposal has been sent in {deal_name} by {action_by}.</p><br>
//<br>
//
//<p></p><p>Thanks for being with us.
//</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
//            ],
//
//            // Proposal approved
//            'proposal_approved' => [
//                'system' => 'A proposal has been approved for this {deal_name} deal.',
//                'subject' => 'A proposal has been approved for this {deal_name} deal.',
//                'content' => '<p><img src="{app_logo}" style="height: 75px"></p>
//<p>
//</p><p><span style="background-color: var(--form-control-bg) ; color: var(--default-font-color) ;">Hi {receiver_name},</span><br></p><p>It\'s good to hear that proposal has been approved for this {deal_name} deal.</p><br>
//<br>
//
//<p></p><p>Thanks for being with us.
//</p><p>Regards,</p><p>{app_name}</p><p></p><p></p>'
//            ],


        ];
    }
}
