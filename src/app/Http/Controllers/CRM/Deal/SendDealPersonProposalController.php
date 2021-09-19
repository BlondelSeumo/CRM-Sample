<?php

namespace App\Http\Controllers\CRM\Deal;

use App\Http\Controllers\Controller;
use App\Mail\DealProposalPersonMail;
use App\Models\CRM\Proposal\Proposal;
use App\Models\CRM\Template\Template;
use App\Services\CRM\Deal\DealService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendDealPersonProposalController extends Controller
{

    public function __construct(DealService $service)
    {
        $this->service = $service;
    }

    public function dealProposal(Request $request)
    {
        try {
            if ($request->proposal_choice_type) {
                $template = Template::where('id', $request->proposal_choice_type)
                    ->first();

                $this->sendMailPerson($request, $template);

            } else {
                $this->sendMailPerson($request);
            }
            return response()->json([
                'status' => true,
                'message' => trans('default.proposal_email_send')
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'error' => trans('default.no_email_settings_found')
            ]);
        }

    }

    public function sendMailPerson($request, $template = null)
    {
        $proposals = $this->service->with(['contactPerson'])
            ->where('id', $request->deal_id)
            ->first();

        $personName = $proposals->contactPerson[0]->name;


        if ($template)
        {
            $templateContent = $template->custom_content ?? $template->default_content;
        } else {
            $templateContent = $request->custom_content;
        }

        $logo = '<img src= "'.asset(config('settings.application.company_logo')).'"/>';
        $templateContent = str_replace('{app_name}', config('settings.application.company_name'), str_replace('{app_logo}', $logo, $templateContent));

        if ($request->email) {
            Mail::to(array($request->email))
                ->send(new DealProposalPersonMail(
                    $request->email,
                    $templateContent ?? $request->custom_content,
                    $personName,
                    $template->subject ?? $request->subject
                ));

            if (Mail::failures()) {
                return response()->json([
                    'status' => false,
                    'message' => trans('default.proposal_email_send_fail')
                ]);
            } else {
                Proposal::create([
                    'subject' => $template->subject ?? $request->subject,
                    'content' => $templateContent ?? $request->custom_content,
                    'status_id' => $request->status_id,
                    'deal_id' => $request->deal_id,
                    'owner_id' => $proposals->owner_id,
                    'sent_at' => now(),
                ]);
            }
        }
    }
}
