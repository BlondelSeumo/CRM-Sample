<?php

namespace App\Http\Controllers\CRM;

use App\Models\Core\Status;
use Illuminate\Http\Request;
use App\Models\CRM\Note\Note;
use App\Models\Core\File\File;
use App\Http\Controllers\Controller;
use App\Models\CRM\Activity\Activity;
use App\Models\Core\Builder\Form\CustomField;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Core\Traits\FileHandler;

class AdditionalController extends Controller
{
    use FileHandler;

    public function activitiesDone(Activity $activity)
    {
        $done = Status::where('name', 'LIKE', '%done')->first()->id;
        $activity->update(['status_id' => $done]);

        return updated_responses('activities_done');
    }

    public function noteUpdate(Request $request, Note $note)
    {
        $note->update($request->all());

        return updated_responses('note');
    }

    public function noteDestroy(Note $note)
    {
        $note->delete();

        return deleted_responses('note');
    }

    public function fileDownload(File $file)
    {
        //$filePath = public_path($file->path);
        $name = basename($file->path);
        return Storage::download($this->removeStorage('public/'.$file->path), $name);

    }

    public function statusesUser()
    {
        return Status::where('type', 'user')->get();
    }

    public function customFieldSearch()
    {
        return CustomField::with(['customFieldType'])
            ->where('context', 'LIKE', '%'.\request()->type.'%')
            ->get();
    }
}
