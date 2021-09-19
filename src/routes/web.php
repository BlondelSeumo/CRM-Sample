<?php

use App\Http\Middleware\PermissionMiddleware;
use App\Http\Controllers\Core\LanguageController;
use App\Http\Controllers\InstallDemoDataController;
use App\Http\Controllers\Setup\AppUpdateController;
use App\Models\Core\Auth\User;


/*
 * This is used to bypass the authentication
 * Remove this during production
 */
//auth()->loginUsingId(1);

Route::get('/',function(){

    if(auth()->check()){
        $user = User::with(['roles'])->where('id', auth()->id())->first();

        if ($user->hasRole(['Agent'])) {
            return redirect(route('persons.lists'));
        } else if ($user->hasRole(['Client'])) {
            return redirect(route('organizations.lists'));
        }
    }

    return redirect('admin/users/login');
});


// for documentation developer purpose

Route::get('doc/core/components', [\App\Http\Controllers\DocumentationController::class, 'index']);
Route::get('doc/core/components/{component_name}', [\App\Http\Controllers\DocumentationController::class, 'show']);

//end

Route::post('test-component', [\App\Http\Controllers\TestingController::class, 'test']);
Route::get('test-component', [\App\Http\Controllers\TestingController::class, 'testValue']);
Route::get('get-test-chart', [\App\Http\Controllers\TestingController::class, 'getTestChart']);

Route::get('dynamic-contnent/{id}', [\App\Http\Controllers\TestingController::class, 'getDynamicValue']);

Route::post('store', [\App\Http\Controllers\TestingController::class, 'store'])->name('store');
Route::get('test-value', [\App\Http\Controllers\TestingController::class, 'getTestValue'])->name('test-value');
Route::get('test-cards', [\App\Http\Controllers\TestingController::class, 'getCardDataForTest'])->name('test-cards');
Route::get('test-calendar-events', [\App\Http\Controllers\TestingController::class, 'getCalendarEventForTest'])->name('test-calendar-events');

// Switch between the included languages
Route::get('lang/{lang}', [LanguageController::class, 'swap'])->name('language.change');

/*
 * All login related route will be go there
 * Only guest user can access this route
 */

Route::group(['middleware' => 'guest', 'prefix' => 'users'], function () {
    include_route_files(__DIR__ . '/user/');
});

Route::group(['middleware' => 'guest', 'prefix' => 'admin/users'], function () {
    include_route_files(__DIR__ . '/login/');
});

/*
 * This route is only for brand redirection
 * And for some additional route
 */
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'authorize']], function () {
    include __DIR__ . '/additional.php';
});

/*
 * Backend Routes
 * Namespaces indicate folder structure
 * All your route in sub file must have a name with not more than 2 index
 * Example: brand.index or dashboard
 * See @var PermissionMiddleware for more information
 */
Route::group(['prefix' => 'admin', 'middleware' => 'admin', 'as' => 'core.'], function () {
    /*
     * (good if you want to allow more than one group in the core,
     * then limit the core features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
    include_route_files(__DIR__ . '/core/');
});

/*
 * CRM Related Routes
 * We separated the route files according to the features
 * Such as contact.php for Person & Organization related routes
 */
Route::group(['middleware' => 'admin'], function () {
    include_route_files(__DIR__ . '/CRM/');
});

Route::group(['middleware' => ['auth', 'authorize']], function () {
    include_route_files(__DIR__ . '/support/');
});

Route::group(['prefix' => 'setup', 'middleware' => ['guest', 'ifNotInstalled']], function () {
    include_route_files(__DIR__ . '/setup/');
});

Route::any('install-demo-data', [InstallDemoDataController::class, 'run'])
    ->name('install-demo-data');

Route::get('app/updates', [AppUpdateController::class, 'index'])
    ->middleware('can:check_for_updates')
    ->name('updates.index');

Route::post('app/updates/install/{version}', [AppUpdateController::class, 'update'])
    ->middleware('can:update_app')
    ->name('updates.install');
