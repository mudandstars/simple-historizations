<?php

namespace Mudandstars\HistorizeModelChanges\Services;

use Mudandstars\HistorizeModelChanges\Actions\GetStringBetweenAction;

class HistorizeParamsService
{
    public function getArray(string $modelPath): array
    {
        $historizeArray = $this->populateArray($modelPath);

        return $historizeArray;
    }

    private function populateArray(string $modelPath): array
    {
        $content = file_get_contents($modelPath);
        $historizeArray = [];

        $arrayInStringForm = GetStringBetweenAction::execute($content, 'protected $historize = [', '];');

        preg_match_all('/\\n/', $arrayInStringForm, $matches, PREG_OFFSET_CAPTURE);

        for ($i = 0; $i < count($matches[0]) - 1; $i++) {
            $currentSubstring = substr($arrayInStringForm, $matches[0][$i][1], $matches[0][$i + 1][1] - $matches[0][$i][1]);

            $key = GetStringBetweenAction::execute($currentSubstring, "'", "' =>");
            $value = GetStringBetweenAction::execute($currentSubstring, "=> '", "'");

            $historizeArray[$key] = $value;
        }

        return $historizeArray;
    }
}
