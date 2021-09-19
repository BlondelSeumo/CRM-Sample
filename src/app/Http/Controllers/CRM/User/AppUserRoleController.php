<?php

namespace App\Http\Controllers\CRM\User;

use App\Exceptions\GeneralException;
use App\Hooks\User\BeforeUserAttachedToRole;
use App\Http\Controllers\Controller;
use App\Models\Core\Auth\Role;
use App\Models\CRM\Notification\ExtendedNotification;
use App\Models\CRM\User\User;
use Illuminate\Http\Request;

class AppUserRoleController extends Controller
{
    public function role()
    {
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['App Admin', 'Manager', 'Agent']);
        })->whereHas('status', function ($query) {
            $query->where('name', 'status_active');
        })->get();
    }

    public function move(Request $request, User $user)
    {
        if ($user->isAppAdmin() && !$user->isInvited()) {
            throw new GeneralException(trans('default.action_not_allowed'));
        }

        $user->deals()->update([
            'owner_id' => $request->new_user_id
        ]);
        $user->roles()->detach();
        $user->delete();
        return updated_responses('user');
    }

    public function destroy(User $user)
    {
        if ($user->isAppAdmin() && !$user->isInvited()) {
            throw new GeneralException(trans('default.action_not_allowed'));
        }

        $user->deals()->delete();
        $user->roles()->detach();
        $user->delete();
        return deleted_responses('user');
    }

    public function attachAppUsers(Role $role, Request $request)
    {
        $request->validate([
            'users' => 'required|array'
        ]);

        $users = $request->all()['users'];
        foreach ($users as $user) {
            $roleId = User::with('roles')->where('id', $user)->first();
            $roleId->roles()->detach($roleId['roles']->first()->id);
        }

        BeforeUserAttachedToRole::new()
            ->setModel($role)
            ->handle();

        $role->users()->detach($request->get('users'));

        $role->users()->attach($request->get('users'));

        return attached_response('users');
    }

    public function notification()
    {
        $user = \App\Models\Core\Auth\User::with(['roles'])->where('id', auth()->id())->first();

        $notification = ExtendedNotification::
            where('user_id', '!=', auth()->id())
            ->whereNull('read_at');

        $notifications = $notification
            ->whereRaw("find_in_set($user->id, audience)")
            ->whereRaw("!FIND_IN_SET($user->id, readers_id)")
            ->with(['user','contextable'])
            ->get();

        foreach ($notifications as $item) {
            $exNotification = ExtendedNotification::find($item->id);
            if ($exNotification->readers_id) {
                $readersId = array_merge(explode(',', $exNotification->readers_id), explode(',', $user->id));
                $implode = implode(',', $readersId);
                $exNotification->update([
                    'readers_id' => $implode
                ]);
            } else {
                $exNotification->update([
                    'readers_id' => $user->id
                ]);
            }

            $audience = explode(',', $item->audience);
            $readers_id = explode(',', $item->readers_id);
            $diff = array_diff($audience, $readers_id);
            if (!count($diff)) {
                $exNotification->update([
                    'read_at' => now()
                ]);
            }
        }

        return $notifications;
    }
}
