<?php

/**
 * This route file contains all
 * of @var App\Models\CRM\Person\Person
 * and @var \App\Models\CRM\Organization\Organization
 * related routes.
 */

use App\Http\Controllers\CRM\Contact\ContactTypeController;
use App\Http\Controllers\CRM\Contact\OrganizationController;
use App\Http\Controllers\CRM\Contact\OrganizationFollowerController;
use App\Http\Controllers\CRM\Contact\PersonController;
use App\Http\Controllers\CRM\Contact\PersonExportController;
use App\Http\Controllers\CRM\Contact\PersonInviteLeadController;
use App\Http\Controllers\CRM\Contact\PhoneEmailTypeController;
use Illuminate\Support\Facades\Route;

// Routes for Persons
Route::group(['middleware' => 'public_access'], function () {
    Route::resource('persons', PersonController::class);

    // Routes for Organizations

    Route::resource('organizations', OrganizationController::class);

    // Routes for Contact Types

    Route::resource('contact/types', ContactTypeController::class)
        ->except('create', 'edit');
});

Route::post('person-invite-lead/{person}', [PersonInviteLeadController::class, 'personInvite'])
    ->name('person.invite_lead');

Route::post('person-import', [PersonController::class, 'importPerson'])
    ->name('persons.import');

Route::get('export/person', [PersonExportController::class, 'export'])
    ->name('person.export');
Route::get('export/person/{skip}', [PersonExportController::class, 'download'])
    ->name('person.export_skip');

Route::get('lead/user/info', [PersonController::class, 'leadUserInfo'])
    ->name('lead.user_info');

Route::post(
    'persons/profile-picture/{person}',
    [PersonController::class, 'profilePicture']
)->name('persons.upload-profile-picture-of');


Route::post(
    'persons/followers/{person}',
    [PersonController::class, 'personFollower']
)->name('persons.sync-followers');

Route::post(
    'persons/contact/sync/{person}',
    [PersonController::class, 'personContactSync']
)->name('persons.sync-contact');

Route::post(
    'persons/organizations/sync/{person}',
    [PersonController::class, 'organizationJobTitleSync']
)->name('persons.sync-organizations');

Route::get(
    'person/{person}/note',
    [PersonController::class, 'personNotes']
)->name('person.get-note');

Route::post(
    'person/note/sync/{person}',
    [PersonController::class, 'personNoteSync']
)->name('person.sync-note');

Route::get(
    'person/{person}/file',
    [PersonController::class, 'personFiles']
)->name('person.get-file');

Route::post(
    'person/file/sync/{person}',
    [PersonController::class, 'personFileSync']
)->name('person.sync-file');

Route::get(
    'person/activities/{person}',
    [PersonController::class, 'personActivities']
)->name('persons.view-activities');

Route::post(
    'person/activities/sync/{person}',
    [PersonController::class, 'personActivitiesSync']
)->name('person.sync-activities');


Route::get('persons/{person}/followers', [PersonController::class, 'personFollowers'])
    ->name('persons.get-followers');


Route::post('organization-import', [OrganizationController::class, 'importOrganization'])
    ->name('organization.import');

//Export organization
Route::get('export/organization', [OrganizationController::class, 'exportOrganization'])
    ->name('organization.export');

Route::get('export/organization/{skip}', [OrganizationController::class, 'download'])
    ->name('organization.export_skip');


Route::post(
    'organizations/profile-picture/{organization}',
    [OrganizationController::class, 'profilePicture']
)->name('organizations.upload-profile-picture-of');

Route::get(
    'organization/{organization}/note',
    [OrganizationController::class, 'orgNotes']
)->name('organization.get-note');

Route::get(
    'organization/{organization}/file',
    [OrganizationController::class, 'orgFiles']
)->name('organization.get-file');

Route::post(
    'organizations/sync/{organization}',
    [OrganizationController::class, 'personJobTitleSync']
)->name('organizations.sync-person');


Route::get(
    'phone/email/type',
    [PhoneEmailTypeController::class, 'index']
)->name('phone_email.viw-types');

Route::get(
    'organization/activities/{organization}',
    [OrganizationController::class, 'organizationActivities']
)->name('organizations.view-activities');

Route::post(
    'organization/activities/sync/{organization}',
    [OrganizationController::class, 'organizationActivitiesSync']
)->name('organization.sync-activities');

Route::post(
    'organization/note/sync/{organization}',
    [OrganizationController::class, 'organizationNoteSync']
)->name('organization.sync-note');

Route::post(
    'organization/file/sync/{organization}',
    [OrganizationController::class, 'organizationFileSync']
)->name('organization.sync-file');

Route::post('organizations/followers/{organization}', [OrganizationFollowerController::class, 'organizationFollower'])
    ->name('organizations.sync-follower');

Route::get('organizations/{organization}/followers', [OrganizationFollowerController::class, 'organizationFollowers'])
    ->name('organizations.get-follower');


