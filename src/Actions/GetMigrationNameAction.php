<?php

namespace Mudandstars\SimpleHistorizations\Actions;

use Carbon\Carbon;

class GetMigrationNameAction
{
    public static function execute(string $modelName): string
    {
        $tableName = GetTableNameAction::execute($modelName);

        $now = Carbon::now();
        $migrationName = $now->year.'_'.MakeTwoDigitNumberAction::execute($now->month).'_'.MakeTwoDigitNumberAction::execute($now->day).'_'.'000000'.'_create_'.$tableName.'_table.php';

        return $migrationName;
    }
}
