<?php

use App\Models\Core\Auth\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('pipex_notification_for_agent.{id}', function ($user,$id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('pipex_notification_for_client.{id}', function ($user,$id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('pipex_notification_for_manager', function ($user) {
    return (bool) User::with(['roles'])->where('id',$user->id)->first()->hasRole(['Manager']);
});

Broadcast::channel('pipex_notification_for_admin', function ($user) {
    return (bool) User::with(['roles'])->where('id',$user->id)->first()->hasRole(['App Admin']);
});

Broadcast::channel('discussion', function ($user) {
    return Auth::check();
});