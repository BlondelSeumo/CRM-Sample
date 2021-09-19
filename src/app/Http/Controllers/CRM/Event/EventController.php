<?php

namespace App\Http\Controllers\CRM\Event;

use App\Http\Controllers\Controller;
use App\Models\CRM\Event\Event;
use App\Services\CRM\Event\EventService;
use App\Http\Requests\CRM\Event\EventRequest as Request;

class EventController extends Controller
{
    public function __construct(EventService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service
            ->with(['contextable'])
            ->paginate(request('per_page', 15));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        return $this->service
            ->find($id);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy(Event $event)
    {
        if ($event->delete()) {
            return deleted_responses('event');
        }
    }
}
