<?php

use Carbon\Carbon;

function diffForHumans($date)
{
    Carbon::setlocale('id');
    return Carbon::parse($date)->diffForHumans();
}

function showDateTime($date, $format = 'd-m-Y H:i:s')
{
    Carbon::setlocale('id');
    return Carbon::parse($date)->translatedFormat($format);
}
