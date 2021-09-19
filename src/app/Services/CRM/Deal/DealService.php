<?php

namespace App\Services\CRM\Deal;

use App\Helpers\CRM\Traits\PaginateTraits;
use App\Mail\CRM\Deal\ClientDealMail;
use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Organization\Organization;
use App\Models\CRM\Person\Person;
use App\Models\CRM\Pipeline\Pipeline;
use App\Helpers\Core\Traits\FileHandler;
use App\Notifications\CRM\Deal\DealNotification;
use App\Services\ApplicationBaseService;
use App\Helpers\CRM\Traits\StoreFileTrait;
use App\Services\CRM\Traits\showDealDetailsTrait;
use App\Http\Resources\CRM\Deal\DealResource;
use Illuminate\Support\Facades\Mail;
use function Aws\map;

class DealService extends ApplicationBaseService
{
    use showDealDetailsTrait, FileHandler, StoreFileTrait, PaginateTraits;

    /**
     * @var Pipeline
     */
    protected $pipeline;

    public function __construct(Deal $deal, Pipeline $pipeline)
    {
        $this->model = $deal;
        $this->pipeline = $pipeline;
    }

    public function showDeal()
    {
        return $deals = $this->model::query()
            ->with([
                // 'pipeline',
                // 'stage' => function ($query) {
                //     $query->select('id', 'name', 'probability', 'pipeline_id');
                // },
                // 'CreatedBy:id,first_name,last_name',
                // 'lostReason:id,lost_reason',
                'status:id,name,class',
                'contextable:id,name',
                'contactPerson:id,name,attach_login_user_id',
                'contactPerson.email',
                'owner.profilePicture',
                'tags',
                'customFields',
                'nextActivity',
                'proposals.CreatedBy',
                'proposals.status',
                'activity.CreatedBy',
                'discussions.responsibleUser.profilePicture',
                'followers' => function ($follower) {
                    $follower->select(['id', 'person_id', 'contextable_id'])
                        ->with([
                            'person' => function ($email) {
                                $email->select(['id', 'name'])
                                    ->with(['email', 'phone', 'profilePicture']);
                            },
                        ]);
                },
                // 'nextActivities'
            ])
            ->withCount(['proposals', 'activity']);
    }

    public function showDealDetails($id)
    {
        return $this->dealDetails($id);
    }

    public function showDealByResource()
    {
        $deals = $this->model::query()
            ->with([
                'pipeline',
                'stage',
                'CreatedBy',
                'lostReason',
                'status',
                'person',
                'organization',
                'owner',
            ])
            ->paginate(15);

        return DealResource::collection($deals);
    }

    public function getPipelineViewData($id)
    {
        return $this->model
            ->where('pipeline_id', $id)
            ->with([
                'status:id,name,class',
                // 'lostReason:id,lost_reason',
                'owner.profilePicture',
                // 'CreatedBy:id,first_name,last_name',
                'contextable:id,name',
                'contextable.profilePicture',
                'contactPerson',
                'tags:id,name,color_code',
                'proposals.CreatedBy',
                'proposals.status',
                'activity.CreatedBy',
                'followers' => function ($follower) {
                    $follower->select(['id', 'person_id', 'contextable_id'])
                        ->with([
                            'person' => function ($email) {
                                $email->select(['id', 'name'])
                                    ->with(['email', 'phone', 'profilePicture']);
                            },
                        ]);
                },
                'discussions.responsibleUser.profilePicture'
            ])
            ->withCount([
                'proposals',
                'doneActivity',
                'toDoActivity'
            ]);
    }

    public function storeDeal($params)
    {
        //create model in deal table
        $params['created_by'] = auth()->user()->id;

        $class = $params['lead_type'] == 1 ?
            new \ReflectionClass(Person::class) :
            new \ReflectionClass(Organization::class);

        $params['contextable_type'] = $class->getName();

        $deal = $this->model->create($params);
        if (array_key_exists('person_id', $params)) {
            $deal->contactPerson()->sync($params['person_id']);
        }
        return $deal;
        //if we need to perform create action for other model in forgeign table
    }

    public function updateDeal($deal, $params)
    {
        $oldContactPerson = count($deal->contactPerson) > 0 ? $deal->contactPerson[0]->id : null;
        //need to perform any check before update

        // prepared deal histories column
        if (request('stage_id') != $deal->stage_id) {
            $params['histories'] = $this->preparedHistoryColForInsert($deal);
        }

        //contextable type column data set
        $class = (array_key_exists('lead_type', $params) && $params['lead_type'] == 1) ?
            new \ReflectionClass(Person::class) :
            new \ReflectionClass(Organization::class);

        if (array_key_exists('lead_type', $params)) {
            $params['contextable_type'] = $class->getName();
        }

        $process = $deal->update($params);

        //need to perform any action after update a deal
        if (array_key_exists('person_id', $params) && !(in_array(request('person_id'), $deal->contactPerson()->pluck('id')->toArray()))) {

            $deal->contactPerson()->sync($params['person_id']);

            $rfc = new \ReflectionClass(Deal::class);
            $deal->revisionHistory()->insert(
                [
                    'revisionable_type' => $rfc->getNamespaceName() . '\Deal',
                    'revisionable_id' => $deal->id,
                    'user_id' => auth()->user()->id,
                    'key' => 'person_id',
                    'old_value' => $oldContactPerson,
                    'new_value' => $params['person_id'],
                    'created_at' => new \DateTime(),
                    'updated_at' => new \DateTime(),
                ]
            );
        }

        //final return
        return $process;
    }

    public function deleteDeal($id)
    {
        //need to perform any check before delete a deal

        return $this->model->where('id', $id)->delete();

        //need to perform any action after update a deal
    }

    public function fileSync($path, $deal)
    {
        foreach ($path as $key => $value) {
            $file_path = $this->fileStore(
                $value,
                'deal'
            );
            $file = $deal->fileUpload()->create([
                'type' => 'deal',
                'path' => $file_path,
            ]);
        }
    }

    public function preparedHistoryColForInsert($deal)
    {
        $newHistory = [];

        // initial update
        if ($deal->histories == null) {
            $histories = collect($deal->pipeline->stage()->get(['id', 'name'])->toArray());

            $newHistory = $histories->map(function ($item) use ($deal) {
                return [
                    'stage_id' => $item['id'],
                    'move_at' => $item['id'] <= request('stage_id') ? now() : null,
                    'call' => 'initial',
                    'last_stage' => $item['id'] == $deal->stage_id,
                    'current_stage' => $item['id'] == request('stage_id'),
                    'timestamps' => ($item['id'] == $deal->stage_id
                        ? [$deal->created_at, now()]
                        : (
                        ($item['id'] < request('stage_id') && $item['id'] > $deal->stage_id)
                            ? [now(), now()]
                            : (
                        ($item['id'] == request('stage_id'))
                            ? [now()]
                            : []
                        )
                        )),
                ];
            });
        } // not initial update
        else {
            $histories = collect(json_decode($deal->histories));
            $newHistory = $histories->map(function ($item, $index) use ($deal, $histories) {
                //update current & last stage timestamps
                if ($item->stage_id == request('stage_id') || $item->stage_id == $deal->stage_id) {
                    array_push($item->timestamps, now());
                }

                // forward
                if (request('stage_id') > $deal->stage_id) {
                    //update in between stage timestamps
                    if ($item->stage_id > $deal->stage_id && $item->stage_id < request('stage_id')) {
                        array_push($item->timestamps, now(), now());
                    }

                    $timestampsArr = $item->timestamps;

                    return [
                        'stage_id' => $item->stage_id,
                        'timestamps' => $timestampsArr,
                        'move_at' => $item->stage_id > request('stage_id')
                            ? $item->move_at
                            : ($item->stage_id < request('stage_id')
                            && $item->stage_id > $deal->stage_id

                                ? $histories[$index - 1]->move_at
                                : ($item->stage_id <= $deal->stage_id
                                    ? $item->move_at
                                    : now())),
                        'call' => 'forward',
                        'last_stage' => $item->stage_id == $deal->stage_id,
                        'current_stage' => $item->stage_id == request('stage_id'),
                    ];
                }
                // backward

                //update in between stage timestamps
                if ($item->stage_id > request('stage_id') && $item->stage_id < $deal->stage_id) {
                    array_push($item->timestamps, now(), now());
                }
                $timestampsArr = $item->timestamps;

                return [
                    'stage_id' => $item->stage_id,
                    'timestamps' => $timestampsArr,
                    'move_at' => $item->stage_id > request('stage_id')
                        ? null
                        : ($item->stage_id == request('stage_id')
                            ? now()
                            : $item->move_at),
                    'call' => 'backward',
                    'last_stage' => $item->stage_id == $deal->stage_id,
                    'current_stage' => $item->stage_id == request('stage_id'),
                ];
            });
        }

        return $newHistory->toArray();
    }

    public function highlightsDealsData()
    {
        return $deals = $this->model::query()
            ->with([
                'tags',
            ]);
    }

    public function contactPersonMailSend(object $deal, string $event)
    {
        $contactPersons = $deal->load('contactPerson');
        $checkAttachLogin = collect($contactPersons->contactPerson)->filter(function ($item) {
            return $item->attach_login_user_id;
        });

        if (count($checkAttachLogin)) {
            $checkUser = $checkAttachLogin[0]->attach_login_user_id;
            $user = User::find($checkUser);
            Mail::to($user->email)->send(new ClientDealMail($user, $deal, $event));

            $audiences = User::where('id', $checkUser)->pluck('id')->toArray();

            notify()
                ->on($event)
                ->with($deal)
                ->audiences($audiences)
                ->send(DealNotification::class);
        }

    }
}
