<?php

namespace App\Helpers\CRM\General;

class ColorCodeHelper
{
    public function randomColorCode()
    {
        $hex = '#';
        foreach (['r', 'g', 'b'] as $color) {
            $value = mt_rand(20, 140);
            $dechex = dechex($value);

            if (strlen($dechex) < 2) {
                $dechex = '0'.$dechex;
            }
            $hex .= $dechex;
        }

        return $hex;
    }
}
