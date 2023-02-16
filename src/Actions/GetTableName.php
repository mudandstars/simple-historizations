<?php

namespace Mudandstars\HistorizeModelChanges\Actions;

class GetTableName
{
    public static function execute(string $model): string
    {
        $migrationNameParts = preg_split('/(?=[A-Z])/', $model);
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
