<?php

namespace Mudandstars\SimpleHistorizations\Actions;

class GetTableNameAction
{
    public static function execute(string $modelName): string
    {
        $migrationNameParts = preg_split('/(?=[A-Z])/', $modelName);
        $migrationName = '';

        foreach ($migrationNameParts as $migrationNamePart) {
            if (! $migrationNamePart) {
                continue;
            }

            if ($migrationName) {
                $migrationName = $migrationName.'_'.strtolower($migrationNamePart);
            } else {
                $migrationName = strtolower($migrationNamePart);
            }
        }

        return $migrationName.'s';
    }
}
