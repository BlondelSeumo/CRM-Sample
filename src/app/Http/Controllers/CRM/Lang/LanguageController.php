<?php

namespace App\Http\Controllers\CRM\Lang;

use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    //
    public function index()
    {
        $langs = scandir(base_path() . '/resources/lang/');
        $preparedLangs = [];
        foreach ($langs as $lang) {
            if (!str_contains($lang, '.')) {
                $preparedLangs[] = ['title' => trans('default.lang', [], $lang), 'key' => $lang];
            }
        };

        return $preparedLangs;
    }

    public function store()
    {
        //user level preference
        \Cookie::queue(\Cookie::forget('user_lang'));
        \Cookie::queue(\Cookie::forever('user_lang', request()->get('lang')));
        return response('successfully updated');
    }
}
