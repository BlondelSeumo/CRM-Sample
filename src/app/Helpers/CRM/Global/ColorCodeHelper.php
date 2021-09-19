<?php

use App\Helpers\CRM\General\ColorCodeHelper;

if (!function_exists('random_color_code'))
{
    function random_color_code()
    {
        return resolve(ColorCodeHelper::class)->randomColorCode();
    }
}
