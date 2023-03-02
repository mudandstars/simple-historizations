<?php

namespace Mudandstars\SimpleHistorizations\Actions;

class GetStringBetweenAction
{
    public static function execute(string $content, string $startSymbol, string $endSymbol): string
    {
        $string = ' '.$content;
        $startPosition = strpos($string, $startSymbol);

        if ($startPosition == 0) {
            return '';
        }

        $startPosition += strlen($startSymbol);
        $length = strpos($string, $endSymbol, $startPosition) - $startPosition;

        return substr($string, $startPosition, $length);
    }
}
