<?php

use Carbon\Carbon;

function diffForHumans($date)
{
    Carbon::setlocale('id');
    return Carbon::parse($date)->diffForHumans();
}

function showDateTime($date, $format = 'Y-m-d H:i')
{
    Carbon::setlocale('id');
    return Carbon::parse($date)->translatedFormat($format);
}
