<?php

namespace App\Http\Controllers\CRM\Proposal;

use App\Mail\DealProposalPersonMail;
use App\Mail\ProposalMail;
use App\Models\CRM\Proposal\Proposal;
use App\Services\CRM\Deal\DealService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Core\Status;

class SendProposalController extends Controller
{
    public function __construct(DealService $dealService, Proposal $service)
    {
        $this->dealService = $dealService;
        $this->service = $service;
    }

    public function sendProposal(Request $request)
    {
        try {
            $proposals = $this->dealService->with(['contactPerson'])
                ->where('id', $request->deal_id)
                ->first();


            $personName = $proposals->contactPerson[0]->name;

            $templateContent = $request->custom_content ?? $request->default_content;

            $logo = '<img src= "'.asset(config('settings.application.company_logo')).'"/>';
            $templateContent = str_replace('{app_name}', config('settings.application.company_name'), str_replace('{app_logo}', $logo, $templateContent));

            if ($request->email) {
                Mail::to([$request->email])
                    ->send(new DealProposalPersonMail($request->email, $templateContent, $personName, $request->subject));

                if (Mail::failures()) {
                    return response()->json([
                        'status' => false,
                        'message' => trans('default.proposal_email_send_fail'),
                    ]);
                } else {
                    $this->service->find($request->id)->update(
                        [
                            'status_id' => $request->status_id,
                            'deal_id' => $request->deal_id,
                            'sent_at' => $request->sent_at
                        ],
                    );
                }
            }

            return response()->json([
                'status' => true,
                'message' => trans('default.proposal_email_send'),
            ]);
        } catch (\Exception $e) {
            // change status sent to draft
            $draft = Status::findByNameAndType('status_draft', 'proposal');
            $this->service->find($request->id)->update(
                [
                    'status_id' => $draft->id,
                    'sent_at' => null
                ]
            );

            return response()->json([
                'status' => false,
                'message' => trans('default.no_email_settings_found'),
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Error does not expose in APP_DEBUG false mode',
            ], 423);
        }
    }
}
