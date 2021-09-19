<?php

namespace App\Http\Controllers\CRM\Status;

use App\Filters\CRM\StatusFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Core\Status;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param StatusFilter $statusFilter
     */
    public function __construct(StatusFilter $statusFilter)
    {
        $this->filter = $statusFilter;
    }

    public function index()
    {
        return Status::query()->filters($this->filter)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        return Status::create($request->all())?
        created_responses('status') :
        failed_responses();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return Status::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        return Status::where('id',$id)->update($request->all())?
        updated_responses('status') :
        failed_responses();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        return Status::where('id',$id)->delete()?
        deleted_responses('status') :
        failed_responses();
    }
}
