<?php

namespace Mudandstars\HistorizeModelChanges\Actions;

use Carbon\Carbon;

class GetMigrationName
{
    public static function execute(string $model): string
    {
        $tableName = GetTableName::execute($model);

        $now = Carbon::now();
        $migrationName = $now->year.'_'.MakeTwoDigitNumber::execute($now->month).'_'.MakeTwoDigitNumber::execute($now->day).'_'.'000000'.'_create_'.$tableName.'_table.php';

        return $migrationName;
    }
}
