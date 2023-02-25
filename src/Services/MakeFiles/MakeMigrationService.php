<?php

namespace Mudandstars\HistorizeModelChanges\Services\MakeFiles;

use Illuminate\Filesystem\Filesystem;
use Mudandstars\HistorizeModelChanges\Actions\GetMigrationNameAction;
use Mudandstars\HistorizeModelChanges\Actions\GetTableNameAction;

class MakeMigrationService extends MakeFileService
{
    public function __construct(Filesystem $files, $logger, string $originalModelName, string $originalModelPath)
    {
        parent::__construct($files, $logger, $originalModelName, $originalModelPath);
    }

    protected function getStubParameters(string $modelName): array
    {
        $name = $this->historizeParams[$modelName];
        $type = $this->modelMigrationColumns[$name];

        return [
            'TABLE' => GetTableNameAction::execute($modelName),
            'COLUMNS' => "\t\t\t\$table->".$type."('previous_".$name."');\n\t\t\t\$table->".$type."('new_".$name."');",
            'RELATED_MODEL' => $this->originalModelName,
        ];
    }

    protected function getSourceFilePath(string $modelName): string
    {
        return base_path('database/migrations/'.GetMigrationNameAction::execute($modelName));
    }

    protected function getStubPath(): string
    {
        return __DIR__.'/../../stubs/historization-migration.stub';
    }
}
