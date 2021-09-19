<?php

namespace App\Models\CRM\Tag;

use App\Models\Core\BaseModel;
use App\Models\CRM\Tag\Traits\Rules\TagRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends BaseModel
{
    use TagRules,HasFactory;

    protected $fillable = ['name', 'color_code'];
    public $timestamps = false;
}
