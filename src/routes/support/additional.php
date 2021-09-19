<?php

use App\Http\Controllers\CRM\Activity\ActivityTypeController;
use App\Http\Controllers\CRM\Contact\OrganizationController;
use App\Http\Controllers\CRM\Contact\OrganizationFollowerController;
use App\Http\Controllers\CRM\Contact\PersonController;
use App\Http\Controllers\CRM\Contact\PhoneController;
use App\Http\Controllers\CRM\Deal\DealController;
use App\Http\Controllers\CRM\Frontend\FrontendController;
use App\Http\Controllers\CRM\Setting\BroadcastSettingController;
use App\Http\Controllers\CRM\Setting\EmailDeliveryCheckController;
use App\Http\Controllers\CRM\Stage\DefaultStageController;
use App\Http\Controllers\CRM\Tags\TagController;
use App\Http\Controllers\CRM\AdditionalController;
use App\Http\Controllers\CRM\User\AppPermissionController;
use App\Http\Controllers\CRM\User\AppUserController;
use App\Http\Controllers\CRM\Setting\SettingsController;
use App\Http\Controllers\CRM\Contact\PhoneEmailTypeController;
use App\Http\Controllers\CRM\Proposal\ProposalController;
use App\Http\Controllers\CRM\User\AppUserRoleController;
use Illuminate\Support\Facades\Route;

Route::get('general-settings', [SettingsController::class, 'index'])
    ->name('settings.general-settings');

Route::get('check-mail-delivery-setting', [EmailDeliveryCheckController::class, 'isExists'])
    ->name('check_mail_delivery_setting');

Route::get('phone/email/type', [PhoneEmailTypeController::class, 'index'])
    ->name('phone_email.type');

Route::post('activities/done/{activity}', [AdditionalController::class, 'activitiesDone'])
    ->name('activities.done');

Route::post('note/update/{note}', [AdditionalController::class, 'noteUpdate'])
    ->name('activities.update-note');
Route::delete('note/delete/{note}', [AdditionalController::class, 'noteDestroy'])
    ->name('activities.delete-note');
Route::get('file/download/{file}', [AdditionalController::class, 'fileDownload'])
    ->name('activities.file-download');;

// All User

Route::get('auth/users', [AppUserController::class, 'crmAuthUsers'])->name('crm.auth_user');

// User Status
Route::get('user/statuses', [AdditionalController::class, 'statusesUser'])->name('statuses-user');
Route::get('users', [AppUserController::class, 'index'])->name('users.index');
Route::get('user-social-links', [AppUserController::class, 'userSocialLink'])->name('user_social_link.index');
Route::patch('user-social-links/update/{userId}', [AppUserController::class, 'userSocialLinkUpdate'])->name('user_social_link.update');

//Role
Route::get('app-permissions', [AppPermissionController::class, 'index'])
    ->name('app.permissions');

// Custom Filed

Route::get('custom-field', [AdditionalController::class, 'customFieldSearch'])
    ->name('custom_field');

// Default Stages

Route::resource('stages-default', DefaultStageController::class);

// Deal Value

Route::get('deal-value', [DealController::class, 'getDealValue'])
    ->name('deal.value');

Route::get('activities/calendar/view', [FrontendController::class, 'activityCalendarView'])
    ->name('activities.calendar')->middleware('can:view_activities');

// Tags

Route::resource('tags', TagController::class)
    ->except('create', 'edit');

//Person Tags

Route::post('persons/tags/{person}', [PersonController::class, 'attachTag'])
    ->name('persons.attach-tag');
Route::put('persons/tags/{person}', [PersonController::class, 'detachTag'])
    ->name('persons.detach-tag');

// Person Details

Route::get('persons/{person}/followers', [PersonController::class, 'personFollowers'])
    ->name('persons.get-followers');

Route::get(
    'person/{person}/file',
    [PersonController::class, 'personFiles']
)->name('person.get-file');

Route::get(
    'person/{person}/note',
    [PersonController::class, 'personNotes']
)->name('person.get-note');


//Organization Details

Route::get('organizations/{organization}/followers', [OrganizationFollowerController::class, 'organizationFollowers'])
    ->name('organizations.get-follower');

Route::get(
    'organization/{organization}/file',
    [OrganizationController::class, 'orgFiles']
)->name('organization.get-file');

Route::get(
    'organization/{organization}/note',
    [OrganizationController::class, 'orgNotes']
)->name('organization.get-note');


// organizations Tags
Route::post(
    'organizations/tags/{organization}',
    [OrganizationController::class, 'attachTag']
)->name('organizations.attach-tag');
Route::put(
    'organizations/tags/{organization}',
    [OrganizationController::class, 'detachTag']
)->name('organizations.detach-tag');

// Deal tags

Route::post(
    'deal/tags/{deal}',
    [DealController::class, 'attachTag']
)->name('deal.attach-tag');

Route::put(
    'deal/tags/{deal}',
    [DealController::class, 'detachTag']
)->name('deal.detach-tag');

// Deal Details

Route::get(
    'deals/{deal}/followers',
    [DealController::class, 'dealFollowers']
)->name('deal.get-followers');

Route::get(
    'deal/{deal}/file',
    [DealController::class, 'dealFiles']
)->name('deal.get-file');

Route::get(
    'deal/{deal}/note',
    [DealController::class, 'dealNotes']
)->name('deal.get-note');

Route::post('multiple/deals/tag/attach', [DealController::class, 'tagsMultipleMarkedDeal'])
    ->name('multiple_deals.tag_attach');
Route::post('multiple/deals/tag/dettach', [DealController::class, 'dettachMultipleDealTag'])
    ->name('multiple_deals.tag_dettach');
Route::post('multiple/deals/delete', [DealController::class, 'deleteMultipleMarkedDeal'])
    ->name('multiple_deals.delete');

Route::post(
    'proposals/tags/{proposal}',
    [ProposalController::class, 'attachTag']
)->name('proposals.tag-attach');

Route::put(
    'proposals/tags/{proposal}',
    [ProposalController::class, 'detachTag']
)->name('proposals.tag-detach');

// Activity Type
Route::resource('activity_types', ActivityTypeController::class);

Route::get('phones', [PhoneController::class, 'index'])->name('phone_list');

Route::get('highlight/deals', [DealController::class, 'dealHighlights'])
    ->name('deals.highlights');

Route::get('system-roles', [AppUserRoleController::class, 'role'])
    ->name('role.system');

Route::post('move-user/{user}', [AppUserRoleController::class, 'move'])
    ->name('user.move');

Route::post('roles/{role}/attach-users', [AppUserRoleController::class, 'attachAppUsers'])
    ->name('roles.attach_app_users_to');

Route::delete('user-delete/{user}', [AppUserRoleController::class, 'destroy'])
    ->name('user_delete');

Route::get('extended-notification', [AppUserRoleController::class, 'notification'])
    ->name('extended_notification');

Route::get('view-broadcast-setting', [BroadcastSettingController::class, 'index'])
    ->name('view-broadcast-setting');

Route::post('broadcast-setting-update', [BroadcastSettingController::class, 'update'])
    ->name('broadcast.setting-update');
// Cache Clear

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('optimize:clear');

    return redirect('/');
});
