<?php


namespace App\Helpers\CRM\Traits;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait StoreFileTrait
{
    public function fileStore(UploadedFile $file, $folder = 'avatar') {
        $name = $file->getClientOriginalName();
        $file->storeAs("{$this->storagePrefix}/{$folder}", $name);
        return Storage::url($folder.'/'.$name);
    }
}
