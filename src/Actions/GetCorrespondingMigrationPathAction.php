<?php

namespace Mudandstars\SimpleHistorizations\Actions;

class GetCorrespondingMigrationPathAction
{
    public static function execute(string $model): string
    {
        $tableName = GetTableNameAction::execute($model);

        $allMigrations = scandir(base_path('database/migrations'));

        foreach ($allMigrations as $migration) {
            if (str_contains($migration, $tableName)) {
                return base_path('database/migrations/'.$migration);
            }
        }
    }
}
