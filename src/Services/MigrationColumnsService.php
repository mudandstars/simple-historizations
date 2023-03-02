<?php

namespace Mudandstars\SimpleHistorizations\Services;

use Mudandstars\SimpleHistorizations\Actions\GetStringBetweenAction;

class MigrationColumnsService
{
    public function getArray(string $migrationPath): array
    {
        $migrationColumns = $this->populateArray($migrationPath);

        return $migrationColumns;
    }

    private function populateArray(string $path): array
    {
        $content = file_get_contents($path);
        $migrationColumns = [];

        $arrayInStringForm = GetStringBetweenAction::execute($content, 'Blueprint $table) {', '});');

        preg_match_all('/\\n/', $arrayInStringForm, $matches, PREG_OFFSET_CAPTURE);
        for ($i = 0; $i < count($matches[0]) - 1; $i++) {
            $currentSubstring = substr($arrayInStringForm, $matches[0][$i][1], $matches[0][$i + 1][1] - $matches[0][$i][1]);

            $key = GetStringBetweenAction::execute($currentSubstring, "('", "')");
            $value = GetStringBetweenAction::execute($currentSubstring, '->', "('");

            if (! $key || $key == 'id') {
                continue;
            }

            $migrationColumns[$key] = $value;
        }

        return $migrationColumns;
    }
}
