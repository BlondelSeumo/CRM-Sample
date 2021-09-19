<?php


namespace App\Models\CRM\Traits;


use App\Models\CRM\Note\Note;

trait NoteRelationshipTrait
{
    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }
}
