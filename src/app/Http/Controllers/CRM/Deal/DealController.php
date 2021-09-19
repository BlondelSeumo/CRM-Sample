<?php

namespace App\Http\Controllers\CRM\Deal;

use App\Filters\CRM\DealFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Deal\DealRequest as Request;
use App\Http\Requests\CRM\Import\ImportDealRequest;
use App\Http\Requests\CRM\Person\FileRequest;
use App\Http\Requests\CRM\Person\FollowerPersonRequest;
use App\Mail\CRM\Deal\DealAssignedMail;
use App\Models\Core\Status;
use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Deal\LostReason;
use App\Models\CRM\Import\DealImport;
use App\Models\CRM\Person\Person;
use App\Models\CRM\Pipeline\Pipeline;
use App\Models\CRM\Stage\Stage;
use App\Models\CRM\Tag\Tag;
use App\Notifications\CRM\Deal\DealNotification;
use App\Services\CRM\Deal\DealService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\HeadingRowImport;
use App\Models\Core\Auth\User;

class DealController extends Controller
{
    public function __construct(DealService $deal, DealFilter $dealFilter)
    {
        $this->service = $deal;
        $this->filter = $dealFilter;
    }

    public function index()
    {
        if (\Request::exists('all')) {
            $open_status_id = Status::findByNameAndType('status_open', 'deal')->id;
            return $this->service
                ->with([
                    'owner' => function ($query) {
                        $query->select('id', 'first_name', 'last_name')
                            ->with([
                                'profilePicture',
                            ]);
                    },
                    'contextable' => function ($query) {
                        $query->select('id', 'name', 'address', 'contact_type_id', 'owner_id')
                            ->with([
                                'contactType:id,name,class', 'profilePicture',
                                'email.type', 'phone.type',
                            ]);
                    },
                    'contactPerson:id,name',
                    'contactPerson.email'
                ])
                ->filters($this->filter)
                ->where('status_id', $open_status_id)
                ->get();
        }

        $order = Str::contains(\Request::get('orderBy'), ['asc', 'desc'])
            ? \Request::get('orderBy')
            : 'desc';

        return $this->service
            ->showDeal()
            ->orderBy('updated_at', $order)
            ->filters($this->filter)
            ->paginate(
                request(
                    'per_page',
                    \Request::get('per_page') ?? 15
                )
            );
    }

    public function store(Request $request)
    {
        //validate data will be storable
        $params = $request->all();


        $deal = $this->service->storeDeal($params);


        if ($request->customs) {
            $this->service->customFieldSync($request->customs, $deal, $this->service);
        }

        $this->service->contactPersonMailSend($deal, 'deal_created');

        notify()
            ->on('deal_created')
            ->with($deal)
            ->send(DealNotification::class);

        return created_responses('deal');
    }

    public function show(Deal $deal)
    {
        return $this->service->showDealDetails($deal->id);
    }

    public function update(Request $request, Deal $deal)
    {
        //validation

        //Validation successfully
        $params = $request->except(['_method']);
        $process = $this->service->updateDeal($deal, $params);

        // update process done,
        // return either success or failed msg
        // on $process value

        Mail::to($deal->owner->email)
            ->send(new DealAssignedMail($deal));

        $this->service->contactPersonMailSend($deal, 'deal_updated');

        notify()
            ->on('deal_updated')
            ->with($deal)
            ->send(DealNotification::class);

        if ($request->customs) {
            $this->service->customFieldSync($request->customs, $deal, $this->service);
        }

        return $process ?
            updated_responses('deal') :
            failed_responses();
    }

    public function destroy(Deal $deal)
    {
        $this->service->contactPersonMailSend($deal, 'deal_deleted');

        notify()
            ->on('deal_deleted')
            ->with($deal)
            ->send(DealNotification::class);

        return $this->service
            ->setModel($deal)
            ->deleteCustomFiled()
            ->delete() ?
            deleted_responses('deal') :
            failed_responses();
    }

    public function showDealByResource()
    {
        return $this->service->showDealByResource();
    }

    public function showPipelineView($pipeline_id = null)
    {
        return $this->service
            ->getPipelineViewData($pipeline_id)
            ->filters($this->filter)
            ->get()
            ->groupBy('stage_id');
    }

    //    Sync Data Start

    public function dealActivitiesSync(\Illuminate\Http\Request $request, Deal $deal)
    {
        if (!$request->status_id) {
            $todo = Status::where('name', 'LIKE', '%todo')->first()->id;
            $request['status_id'] = $todo;
        }
        $activity = $deal->activity()->create($request->all());

        if ($request->person_id) {
            $activity->participants()->sync($request->person_id);
        }

        if ($request->owner_id) {
            $activity->collaborators()->sync($request->owner_id);
        }

        return created_responses('activity');
    }

    public function dealFollowerSync(FollowerPersonRequest $request, Deal $deal)
    {
        if ($request->has('person_id')) {
            $data = $this->service->prepareFollowersDataBeforeSync($request->person_id);
            $this->service->followerSyncAll($deal->followers(), $data);
        }

        return updated_responses('synchronization');
    }

    public function attachTag(\Illuminate\Http\Request $request, Deal $deal)
    {
        $check = $deal->tags()->attach($request->tag_id);
        // if ($check > 0) {
        $rfc = new \ReflectionClass(Deal::class);
        $deal->revisionHistory()->insert(
            [
                'revisionable_type' => $rfc->getNamespaceName() . '\Deal',
                'revisionable_id' => $deal->id,
                'user_id' => auth()->user()->id,
                'key' => 'tag_added',
                'new_value' => $request->tag_id,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ]
        );

        return updated_responses('deal');
        // }
    }

    public function detachTag(\Illuminate\Http\Request $request, Deal $deal)
    {
        $check = $deal->tags()->detach($request->tag_id);
        // if ($check > 0) {
        $rfc = new \ReflectionClass(Deal::class);
        $deal->revisionHistory()->insert(
            [
                'revisionable_type' => $rfc->getNamespaceName() . '\Deal',
                'revisionable_id' => $deal->id,
                'user_id' => auth()->user()->id,
                'key' => 'tag_removed',
                'new_value' => $request->tag_id,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ]
        );

        return updated_responses('deal');
        // }
    }

    public function syncOrg(Deal $deal, Request $request)
    {
        $deal->update(['organization_id' => $request->organization_id]);
    }

    public function dealNoteSync(\Illuminate\Http\Request $request, Deal $deal)
    {
        $note = $deal->notes()->create($request->all());

        return [
            'status' => 200,
            'message' => trans('default.note_has_been_created_successfully'),
        ];
    }

    public function dealFileSync(FileRequest $request, Deal $deal)
    {
        $this->service->fileSync($request->path, $deal);

        return [
            'status' => 200,
            'message' => trans('default.deal_file_has_been_uploaded_successfully')
        ];
    }

    public function dealActivities(Deal $deal)
    {
        return $deal->activity()
            ->with([
                'participants',
                'collaborators',
            ])
            ->filters($this->filter)
            ->get();
    }

    public function dealNotes(Deal $deal)
    {
        return $deal->notes()
            ->filters($this->filter)
            ->get();
    }

    public function dealFiles(Deal $deal)
    {
        return $deal->files()
            ->where('type', '!=', 'profile_picture')
            ->filters($this->filter)
            ->get();
    }

    public function dealPersonDelete(\Illuminate\Http\Request $request, Deal $deal)
    {
        $deal->contactPerson()->detach($request->person_id);

        return deleted_responses('person');
    }

    public function dealOrganizationDelete(Deal $deal)
    {
        $deal->update(['organization_id' => null]);

        return deleted_responses('organization');
    }

    public function getDealValue()
    {
        $deal['max_deal_value'] = $this->service->max('value') ?? 0;
        $deal['min_deal_value'] = $this->service->min('value') ?? 0;

        return $deal;
    }

    public function syncParticipants(Request $request, Deal $deal)
    {
        if ($request->has('person_id')) {
            $deal->participants()->sync($request->person_id);
        }

        return updated_responses('synchronization');
    }

    public function importDeal(ImportDealRequest $request)
    {
        // get current maximum execution time value
        $current_execution_time = ini_get('max_execution_time');

        // maximum execution time is to set 300s
        ini_set('max_execution_time', 300);

        //get current $memory_limit
        $current_memory_limit = ini_get('memory_limit');

        //set memory limit to 512M
        ini_set('memory_limit', '512M');

        $file = $request->file('import_file');

        $import = new DealImport();
        $headings = (new HeadingRowImport)->toArray($file);

        $missingField = array_diff($import->requiredHeading, $headings[0][0]);
        if (count($missingField) > 0) {
            return response(collect($missingField)->values(), 423);
        }
        $import->import($file);
        $failures = $import->failures();
        // after import action complete
        // set to previous maximum execution time value
        ini_set('max_execution_time', $current_execution_time);
        //set its previous state of memory limit
        ini_set('memory_limit', $current_memory_limit);
        //partial import
        if ($failures->count() > 0) {
            $stat = import_failed($file, $failures);
            return [
                'status' => 200,
                'message' => trans('default.deals') . ' ' . trans('default.partially_imported'),
                'stat' => $stat
            ];
        }

        return [
            'status' => 200,
            'message' => trans('default.deals') . ' ' . trans('default.has_been_imported_successfully')
        ];
    }

    public function getDealByPersonOrg($id, Request $request)
    {
        $order = Str::contains(\Request::get('orderBy'), ['asc', 'desc'])
            ? \Request::get('orderBy')
            : 'desc';

        $collection = $this->service
            ->showDeal()
            ->filters($this->filter)
            ->orderBy('updated_at', $order)
            ->where('contextable_id', $id)
            ->get()
            ->values();

        return $this->service->paginate($collection, request('per_page', 15), request('page', 1));
    }

    public function dealFollowers(Deal $deal)
    {
        $followers = $deal
            ->load(
                [
                    'followers.person' => function ($person) {
                        $person
                            ->withCount(['openDeals', 'closeDeals'])
                            ->with(['contactType:id,name,class', 'owner:id,first_name,last_name', 'tags:id,name,color_code']);
                    }
                ]
            )->followers;

        $result = $followers->map(
            function ($item) {
                return $item->person;
            }
        );

        return $this->service->paginate($result, request('per_page', 15), request('page', 1));
    }

    public function dealAllParticipants(Deal $deal)
    {
        $participants = $deal
            ->load(
                [
                    'activity.participants' => function ($person) {
                        $person
                            ->withCount(['openDeals', 'closeDeals'])
                            ->with(
                                [
                                    'contactType:id,name,class',
                                    'owner:id,first_name,last_name',
                                    'tags:id,name,color_code'
                                ]
                            );
                    }
                ]
            )
            ->activity
            ->pluck('participants')
            ->flatten()
            ->unique('id')
            ->flatten();

        return $this->service->paginate($participants, request('per_page', 15), request('page', 1));
    }

    public function dealAllCollaborators(Deal $deal)
    {
        $collaborators = $deal
            ->load(['activity.collaborators'])
            ->activity
            ->pluck('collaborators')
            ->flatten()
            ->unique('id')
            ->values();

        return $this->service->paginate($collaborators, request('per_page', 15), request('page', 1));
    }

    public function moveMultipleMarkedDeal()
    {
        $deals = request()->input('deals');
        $data = request()
            ->only([
                'pipeline_id',
                'stage_id'
            ]);
        $dealsCount = 0;
        foreach ($deals as $deal) {
            if (Deal::find($deal)->update($data)) $dealsCount++;
        }
        return $dealsCount > 0 ?
            updated_responses('deal') :
            failed_responses();
    }

    public function deleteMultipleMarkedDeal()
    {
        $deals = request()->input('deals');
        return Deal::whereIn('id', $deals)->delete();
    }

    public function tagsMultipleMarkedDeal()
    {
        $deals = Deal::whereIn('id', request()->input('deals'))->get();
        $tagId = request()->input('tag_id');
        foreach ($deals as $deal) {
            try {
                $deal->tags()->attach($tagId);
            } catch (\Illuminate\Database\QueryException $e) {
                // when we try attach tag which is already attached
                // throw an exception
                // which is not effect our logic.
            }
        }
        return updated_responses('deal');
    }

    public function dettachMultipleDealTag()
    {
        $deals = Deal::whereIn('id', request()->input('deals'))->get();
        $tagId = request()->input('tag_id');
        foreach ($deals as $deal) {
            try {
                $deal->tags()->detach($tagId);
            } catch (\Illuminate\Database\QueryException $e) {
            }
        }
        return updated_responses('deal');
    }

    public function dealHighlights()
    {
        return $this->service
            ->highlightsDealsData()
            ->filters($this->filter)
            ->get();
    }

    public function revision(Deal $deal)
    {
        $dirtyData = $deal->revisionHistory;
        $preparedRevisionHistory = [];
        $contextable_old_value = null;
        $contextable_new_value = null;
        foreach ($dirtyData as $history) {
            $data = [];
            $data['responsible_user'] = User::with(['profilePicture', 'profile'])->find($history->user_id);
            if ($history->key == 'stage_id') {
                $oldValue = Stage::find($history->old_value);
                $newValue = Stage::find($history->new_value);
                if (isset($oldValue->id) && isset($newValue->id))
                {
                    $data['changed_key'] = 'stage';
                    $data['old_value'] = $oldValue->only('id', 'name');
                    $data['new_value'] = $newValue->only('id', 'name');
                }
            } elseif ($history->key == 'owner_id' && User::find($history->old_value) && User::find($history->new_value)) {
                $oldValue = User::find($history->old_value);
                $newValue = User::find($history->new_value);
                if (isset($oldValue->id) && isset($newValue->id))
                {
                    $data['changed_key'] = 'owner';
                    $data['old_value'] = $oldValue->only('id', 'name');
                    $data['new_value'] = $newValue->only('id', 'name');
                }
            } elseif ($history->key == 'contextable_type') {
                $data['changed_key'] = 'lead_type';
                $data['old_value'] = Str::afterLast($history->old_value, '\\');
                $data['new_value'] = Str::afterLast($history->new_value, '\\');
                $contextable_old_value = $history->old_value;
                $contextable_new_value = $history->new_value;
            } elseif ($history->key == 'contextable_id') {
                $contextable_old_value = $contextable_old_value ? $contextable_old_value : $deal->contextable_type;
                $contextable_new_value = $contextable_new_value ? $contextable_new_value : $deal->contextable_type;
                $oldValue = app($contextable_old_value)::find($history->old_value);
                $newValue = app($contextable_new_value)::find($history->new_value);
                if (isset($oldValue->id) && isset($newValue->id))
                {
                    $data['changed_key'] = 'lead';
                    $data['old_value'] = $oldValue->only('id', 'name');
                    $data['new_value'] = $newValue->only('id', 'name');
                }
                $contextable_old_value = null;
                $contextable_new_value = null;
            } elseif ($history->key == 'person_id') {
                $personNameSpace = new \ReflectionClass(Person::class);
                $oldValue = app($personNameSpace->getName())::find($history->old_value);
                $newValue = app($personNameSpace->getName())::find($history->new_value);
                if ($history->old_value && $history->new_value && isset($oldValue->id) && isset($newValue->id))
                {
                    $data['changed_key'] = 'contact person';
                    $data['old_value'] = $oldValue->only('id', 'name');
                    $data['new_value'] = $newValue->only('id', 'name');
                } elseif (!$history->old_value && isset($newValue->id)) {
                    $data['contact_person_added'] = 'contact person added';
                    $data['contact_person_add'] = $newValue->only('id', 'name');
                } elseif (!$history->new_value && isset($oldValue->id)) {
                    $data['contact_person_removed'] = 'contact person removed';
                    $data['contact_person_remove'] = $oldValue->only('id', 'name');
                }
            } elseif ($history->key == 'pipeline_id') {
                $oldValue = Pipeline::find($history->old_value);
                $newValue = Pipeline::find($history->new_value);
                if (isset($oldValue->id) && isset($newValue->id))
                {
                    $data['changed_key'] = 'pipeline';
                    $data['old_value'] = $oldValue->only('id', 'name');
                    $data['new_value'] = $newValue->only('id', 'name');
                }
            } elseif ($history->key == 'tag_added') {
                $data['tag_added'] = 'tag_added';
                $data['tag'] = Tag::find($history->new_value);
            } elseif ($history->key == 'tag_removed') {
                $data['tag_removed'] = 'tag_removed';
                $data['tag'] = Tag::find($history->new_value);
            } elseif ($history->key == 'status_id') {
                $data['status_change'] = 'Status';
                $data['old_value'] = Status::find($history->old_value);
                $data['new_value'] = Status::find($history->new_value);
            } elseif ($history->key == 'lost_reason_id') {
                $newValue = LostReason::find($history->new_value);
                if (isset($newValue->id))
                {
                    $data['lost_reason_add'] = 'lost reason';
                    $data['new_value'] = $newValue->lost_reason;
                }
            }elseif ($history->key == 'expired_at') {
                $data['changed_key'] = 'expired_at';
                $data['old_value'] = $history->old_value;
                $data['new_value'] = $history->new_value;
            }elseif ($history->key == 'value') {
                $data['changed_key'] = 'deal value';
                $data['old_value'] = $history->old_value;
                $data['new_value'] = $history->new_value;
            }
            $data['created_at'] = $history->created_at;
            array_push($preparedRevisionHistory, $data);
        };
        return $preparedRevisionHistory;
    }
}
