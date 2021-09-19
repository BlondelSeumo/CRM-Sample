<?php

namespace App\Http\Controllers\CRM\Contact;

use App\Http\Controllers\Controller;
use App\Models\CRM\Contact\PhoneEmailType;
use Illuminate\Http\Request;

class PhoneEmailTypeController extends Controller
{

    public function index()
    {
        return PhoneEmailType::all();
    }
}
