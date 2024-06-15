<?php

namespace App\Traits;

trait BgColorTrait
{
    public function randomBgColor($threshold = 127)
    {
        $dt = '';
        for ($o = 1; $o <= 3; $o++) {
            $dt .= str_pad(dechex(mt_rand(0, $threshold)), 2, '0', STR_PAD_LEFT);
        }

        return '#'.$dt;
    }
}
