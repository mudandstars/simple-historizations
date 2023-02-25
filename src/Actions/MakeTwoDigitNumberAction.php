<?php

namespace Mudandstars\HistorizeModelChanges\Actions;

class MakeTwoDigitNumberAction
{
    public static function execute(int $number): string
    {
        return str_pad(strval($number), 2, '0', STR_PAD_LEFT);
    }
}
