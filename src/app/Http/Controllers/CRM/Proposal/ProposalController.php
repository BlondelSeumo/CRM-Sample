<?php

namespace App\Http\Controllers\CRM\Proposal;

use App\Models\Core\Status;
use Config;
use App\Filters\CRM\ProposalFilter;
use App\Http\Controllers\Controller;
use App\Mail\DealProposalPersonMail;
use Illuminate\Support\Facades\Mail;
use App\Models\CRM\Proposal\Proposal;
use App\Services\CRM\Proposal\ProposalService;
use App\Http\Requests\CRM\Proposal\ProposalRequest as Request;
use Illuminate\Support\Str;

class ProposalController extends Controller
{
    public function __construct(ProposalService $proposalService, ProposalFilter $filter)
    {
        $this->service = $proposalService;
        $this->filter = $filter;
    }

    public function index()
    {
        $order = Str::contains(\Request::get('orderBy'), ['asc', 'desc'])
            ? \Request::get('orderBy')
            : 'desc';

        if (\Request::exists('all'))
        {
            return $this->service
                ->with([
                    'status',
                    'deal.contactPerson.email',
                    'deal.contactPerson.profilePicture',
                    'tags:id,name,color_code',
                    'CreatedBy:id,first_name,last_name',
                    'owner' => function ($query) {
                        $query->select('id', 'first_name', 'last_name')
                            ->with([
                                'profilePicture',
                            ]);
                    },
                ])
                ->orderBy('updated_at', $order)
                ->filters($this->filter)
                ->get()
                ->groupBy('status_id');
        }
        return $this->service
            ->with([
                'status',
                'deal.contactPerson.email',
                'tags:id,name,color_code',
                'owner:id,first_name,last_name',
                'CreatedBy:id,first_name,last_name',
            ])
            ->orderBy('updated_at', $order)
            ->filters($this->filter)
            ->paginate(
                request('per_page', '15')
            );
    }

    public function create()
    {
        return view('crm.proposals.send-proposal');
    }

    public function store(Request $request)
    {
        try {
            $templateContent = $request->custom_content;
            $proposals = $this->service->save();

            $logo = '<img src= "' . asset(config('settings.application.company_logo')) . '"/>';
            $templateContent = str_replace('{app_name}', config('settings.application.company_name'), str_replace('{app_logo}', $logo, $templateContent));
            $personName = $proposals->deal->contactPerson[0]->name;

            if ($request->email) {
                Mail::to([$request->email])
                    ->send(new DealProposalPersonMail($request->email, $templateContent, $personName, $request->subject));

                if (Mail::failures()) {
                    return response()->json([
                        'status' => false,
                        'message' => trans('default.proposal_email_send_fail'),
                    ]);
                }
            }

            return response()->json([
                'status' => true,
                'message' => trans('default.proposal_email_send'),
            ]);
        } catch (\Exception $e) {
            $draft = Status::findByNameAndType('status_draft', 'proposal');
            $proposals->update(
                ['status_id' => $draft->id],
                ['sent_at' => null]
            );

            return response()->json([
                'status' => false,
                'error' => trans('default.email_setting_is_not_correct'),
            ]);
        }

        return created_responses('proposal');
    }

    public function show($id)
    {
        return $this->service
            ->with([
                'status',
                'deal.contactPerson.email',
                'tags:id,name,color_code',
                'owner:id,first_name,last_name',
                'CreatedBy:id,first_name,last_name',
            ])->find($id);
    }

    public function edit($id)
    {
        return view('crm.proposals.send-proposal', compact('id'));
    }

    public function update(Request $request, $id)
    {
        $this->service->find($id)->update($request->all());

        return updated_responses('proposal');
    }

    public function destroy($id)
    {
        if ($this->service->find($id)->delete()) {
            return deleted_responses('proposal');
        }
    }

    public function attachTag(\Illuminate\Http\Request $request, Proposal $proposal)
    {
        $proposal->tags()->attach($request->tag_id);

        return updated_responses('proposal');
    }

    public function detachTag(\Illuminate\Http\Request $request, Proposal $proposal)
    {
        $proposal->tags()->detach($request->tag_id);

        return updated_responses('proposal');
    }
}
