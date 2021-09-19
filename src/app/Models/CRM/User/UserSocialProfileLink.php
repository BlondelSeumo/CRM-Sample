<?php

namespace App\Models\CRM\User;

use Illuminate\Database\Eloquent\Model;

class UserSocialProfileLink extends Model
{
    protected $fillable = [
        'user_id',
        'facebook',
        'twitter',
        'linkedin',
        'behance',
        'youtube',
        'instagram',
        'dribble',
        'google_plus',
        'skype',
        'pinterest'
    ];

}
