<?php

namespace App\Http\Controllers\CRM\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile()
    {
       return view('auth.profile');
    }
}
