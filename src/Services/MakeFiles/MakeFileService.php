<?php

namespace Mudandstars\SimpleHistorizations\Services\MakeFiles;

use Illuminate\Filesystem\Filesystem;
use Mudandstars\SimpleHistorizations\Actions\GetCorrespondingMigrationPathAction;
use Mudandstars\SimpleHistorizations\Services\HistorizeParamsService;
use Mudandstars\SimpleHistorizations\Services\MigrationColumnsService;

abstract class MakeFileService
{
    protected $files;

    protected $logger;

    protected $originalModelName;

    protected $historizeParams;

    protected $modelMigrationColumns;

    public function __construct(Filesystem $files, $logger, string $originalModelName, string $originalModelPath)
    {
        $this->files = $files;

        $this->logger = $logger;

        $this->originalModelName = $originalModelName;

        $historizeParamsService = new HistorizeParamsService();
        $this->historizeParams = $historizeParamsService->getArray($originalModelPath);

        $migrationColumnsService = new MigrationColumnsService();
        $this->modelMigrationColumns = $migrationColumnsService->getArray(GetCorrespondingMigrationPathAction::execute($originalModelName));
    }

    public function makeFile(string $modelName): void
    {
        $path = $this->getSourceFilePath($modelName);
        $this->logger->info("Source Path: {$path}");

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceFile($modelName);

        if (! $this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->logger->info("File : {$path} created");
        } else {
            $this->logger->info("File : {$path} already exits");
        }
    }

    protected function makeDirectory(string $path): void
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }
    }

    protected function getSourceFile(string $model): string
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubParameters($model));
    }

    public function getStubContents(string $stubPath, array $stubVariables = []): string
    {
        $contents = file_get_contents($stubPath);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('{{ '.$search.' }}', $replace, $contents);
        }

        return $contents;
    }

    abstract protected function getStubParameters(string $modelName): array;

    abstract protected function getStubPath(): string;

    abstract protected function getSourceFilePath(string $modelPath): string;

    protected function castsAttributes(string $modelName): string
    {
        $columnName = $this->historizeParams[$modelName];
        $columnType = $this->modelMigrationColumns[$columnName];

        switch($columnType) {
            case 'boolean':
                return "\tprotected \$casts = [\n\t\t'created_at' => 'datetime',\n\t\t'previous_".$columnName."' => 'boolean',\n\t\t'new_".$columnName."' => 'boolean',\n\t];";
            case 'date':
                return "\tprotected \$casts = [\n\t\t'created_at' => 'datetime',\n\t\t'previous_".$columnName."' => 'datetime',\n\t\t'new_".$columnName."' => 'datetime',\n\t];";
            case 'timestamp':
                return "\tprotected \$casts = [\n\t\t'created_at' => 'datetime',\n\t\t'previous_".$columnName."' => 'datetime',\n\t\t'new_".$columnName."' => 'datetime',\n\t];";
            case 'timestampTz':
                return "\tprotected \$casts = [\n\t\t'created_at' => 'datetime',\n\t\t'previous_".$columnName."' => 'datetime',\n\t\t'new_".$columnName."' => 'datetime',\n\t];";
            default:
                return "\tprotected \$casts = [\n\t\t'created_at' => 'datetime',\n\t];";
        }
    }
}
