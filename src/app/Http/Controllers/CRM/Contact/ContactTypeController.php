<?php

namespace App\Http\Controllers\CRM\Contact;


use App\Filters\CRM\ContactTypeFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Contact\ContactTypeRequest as Request;
use App\Models\CRM\Contact\ContactType;
use App\Services\CRM\Contact\ContactTypeService;

class ContactTypeController extends Controller
{
    public function __construct(ContactTypeService $service, ContactTypeFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->paginate(request('per_page', 15));
    }

    public function store(Request $request)
    {
        $this->service->save();
        return created_responses('lead_group');
    }

    public function show($id)
    {
        return $this->service
               ->find($id);
    }

    public function update(Request $request, ContactType $type)
    {
        $type->update($request->all());
        return updated_responses('lead_group');
    }

    public function destroy(ContactType $type)
    {
        if ($type->delete()) {
            return deleted_responses('lead_group');
        }
    }

    public function getLeadGroup()
    {
        return $this->service
            ->get();
    }
}
