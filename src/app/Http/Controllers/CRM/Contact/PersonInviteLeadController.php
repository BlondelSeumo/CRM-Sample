<?php

namespace App\Http\Controllers\CRM\Contact;

use App\Http\Controllers\Controller;

use App\Models\Core\Auth\Role;
use App\Models\Core\Status;
use App\Models\CRM\Person\Person;
use App\Services\CRM\Contact\PersonInviteLeadService;
use Illuminate\Http\Request;

class PersonInviteLeadController extends Controller
{
    public function __construct(PersonInviteLeadService $service)
    {
        $this->service = $service;
    }

    public function personInvite(Request $request, Person $person)
    {

        $request->validate([
            'email' => 'required|email|unique:users,email'
        ]);
        $status = Status::findByNameAndType('status_invited')->id;
        $checkEmail = $person->email()->whereValue($request->email)->first();
        $invitation_token = base64_encode($request->get('email') . '-invitation-from-token');

        if ($checkEmail) {
            $user = $this->service
                ->save(
                    array_merge($request->only('email'), [
                        'status_id' => $status,
                        'invitation_token' => $invitation_token
                    ])
                );
            $person->update(['attach_login_user_id' => $user->id]);
            $this->service->invite();

        } else {
            return response()->json([
                'message' => trans('default.this_lead_has_no_email')
            ], 422);
        }
        return response()->json([
            'status' => true,
            'message' => trans('default.invite_user_response')
        ]);

    }
}
