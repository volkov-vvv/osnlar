<?php

/**
 * contrast_color
 *
 * На основе введенного цвета выдает наиболее контрастный: черный или белый
 *
 * @param $hex
 *
 */

function contrast_color($hex)
{
    $hex = trim($hex, ' #');

    $size = strlen($hex);
    if ($size == 3) {
        $parts = str_split($hex, 1);
        $hex = '';
        foreach ($parts as $row) {
            $hex .= $row . $row;
        }
    }

    $dec = hexdec($hex);
    $rgb = array(
        0xFF & ($dec >> 0x10),
        0xFF & ($dec >> 0x8),
        0xFF & $dec
    );

    $contrast = (round($rgb[0] * 299) + round($rgb[1] * 587) + round($rgb[2] * 114)) / 1000;
    return ($contrast >= 125) ? '#000' : '#fff';
}
