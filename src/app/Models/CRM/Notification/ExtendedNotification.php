<?php

namespace App\Models\CRM\Notification;

use App\Models\Core\Auth\User;
use App\Models\Core\BaseModel;
use App\Models\CRM\Deal\Deal;

class ExtendedNotification extends BaseModel
{

    protected $fillable = [
        'contextable_type',
        'contextable_id',
        'user_id',
        'audience',
        'message',
        'readers_id',
        'read_at',
    ];

    public function contextable()
    {
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class ,'user_id');
    }
}
