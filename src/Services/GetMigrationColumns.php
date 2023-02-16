<?php

namespace Mudandstars\HistorizeModelChanges\Services;

class GetMigrationColumns
{
    public function getArray(string $path): array
    {
        $migrationColumns = $this->populateArray($path);

        return $migrationColumns;
    }

    private function populateArray(string $path): array
    {
        $content = file_get_contents($path);
        $migrationColumns = [];

        $arrayInStringForm = $this->getStringBetween($content, 'Blueprint $table) {', '});');

        preg_match_all('/\\n/', $arrayInStringForm, $matches, PREG_OFFSET_CAPTURE);
        for ($i = 0; $i < count($matches[0]) - 1; $i++) {
            $currentSubstring = substr($arrayInStringForm, $matches[0][$i][1], $matches[0][$i + 1][1] - $matches[0][$i][1]);

            $key = $this->getStringBetween($currentSubstring, "('", "')");
            $value = $this->getStringBetween($currentSubstring, "->", "('");

            if (!$key || $key == 'id') {
                continue;
            }

            $migrationColumns[$key] = $value;
        }

        return $migrationColumns;
    }

    private function getStringBetween(string $content, string $start, string $end): string
    {
        $string = ' '.$content;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        return substr($string, $ini, $len);
    }
}
