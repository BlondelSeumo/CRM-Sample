<?php

namespace App\Models\CRM\Discussion;

use App\Models\Core\Auth\User;
use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discussion extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'commented_by',
        'comment_body',
        'commentable_type',
        'commentable_id',
        'comment_id_of_reply'
    ];

    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'commented_by', 'id');
    }
    public function commentable()
    {
        return $this->morphTo();
    }
}
