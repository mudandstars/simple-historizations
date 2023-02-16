<?php

namespace Mudandstars\HistorizeModelChanges\Actions;

use Carbon\Carbon;

class GetMigrationName
{
    public function execute(string $model): string
    {
        $tableName = GetTableName::execute($model);

        $now = Carbon::now();
        $migrationName = $now->year.'_'.$this->twoDigitNumber($now->month).'_'.$this->twoDigitNumber($now->day).'_'.'000000'.'_create_'.$tableName.'_table.php';

        return $migrationName;
    }

    private function twoDigitNumber(int $number): string
    {
        return str_pad(strval($number), 2, '0', STR_PAD_LEFT);
    }
}
