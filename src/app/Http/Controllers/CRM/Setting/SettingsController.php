<?php

namespace App\Http\Controllers\CRM\Setting;

use App\Http\Controllers\Controller;


class SettingsController extends Controller
{
    public function index()
    {
        return [
            'time_format' => array_map(function ($format) {
                return ['id' => $format, 'value' => trans('default.'.$format)];
            }, config('settings.time_format')),

            'currency_position' => array_map(function ($format) {
                return ['id' => $format, 'value' => trans('default.'.$format)];
            }, config('settings.currency_position')),

            'date_format' => array_map(function ($format) {
                return ['id' => $format, 'value' => trans('default.'.$format)];
            }, config('settings.date_format')),

            'decimal_separator' => array_map(function ($format) {
                return ['id' => $format, 'value' => trans('default.'.$format)];
            }, config('settings.decimal_separator')),

            'thousand_separator' => array_map(function ($format) {
                return ['id' => $format, 'value' => trans('default.'.$format)];
            }, config('settings.thousand_separator')),

            'number_of_decimal' => array_map(function ($format) {
                return ['id' => $format, 'value' => trans('default.'.$format)];
            }, config('settings.number_of_decimal')),

            'time_zones' => array_map(function ($format) {
                return [
                    'id' => $format,
                    'value' => $format
                ];
            }, timezone_identifiers_list()),
        ];
    }
}
