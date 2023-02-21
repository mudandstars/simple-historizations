<?php

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Mudandstars\HistorizeModelChanges\Actions\GetCorrespondingMigrationPath;
use Mudandstars\HistorizeModelChanges\Actions\GetMigrationName;
use Mudandstars\HistorizeModelChanges\Actions\GetTableName;
use Mudandstars\HistorizeModelChanges\Services\GetHistorizeParams;
use Mudandstars\HistorizeModelChanges\Services\GetMigrationColumns;

class MakeHistorizationFiles extends Command
{
    protected $signature = 'make-historization-files';

    protected $description = 'Makes all migration- and model-files necessary for the historization feature.';

    protected $files;

    protected $currentOriginalModel;

    protected $historizeParams;

    protected $modelMigrationColumns;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function handle(GetHistorizeParams $getHistorizeParams, GetMigrationColumns $migrationColumnsService)
    {
        $modelsPath = app_path('/Models/');
        foreach ($this->getModelsImplementingTrait($modelsPath) as $modelPath) {
            $this->currentOriginalModel = trim($modelPath, '.php');
            $this->modelMigrationColumns = $migrationColumnsService->getArray(GetCorrespondingMigrationPath::execute($this->currentOriginalModel));
            $this->historizeParams = $getHistorizeParams->getArray($modelsPath.$modelPath);

            foreach (array_keys($this->historizeParams) as $model) {
                $this->makeFile($model, 'model');
                $this->makeFile($model, 'migration');
            }
        }

        return COMMAND::SUCCESS;
    }

    protected function getModelsImplementingTrait(string $modelsPath): array
    {
        $modelsImplementingTrait = [];

        foreach (scandir($modelsPath) as $modelPath) {
            if ($modelPath == '.' || $modelPath == '..') {
                continue;
            }

            if (str_contains(file_get_contents($modelsPath.$modelPath), 'use HistorizeModelChange')) {
                array_push($modelsImplementingTrait, $modelPath);
            }
        }

        return $modelsImplementingTrait;
    }

    protected function makeFile(string $model, string $type): void
    {
        $path = $this->getSourceFilePath($model, $type);

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceFile($model, $type);

        if (! $this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }
    }

    protected function makeDirectory(string $path): string
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    public function getSourceFilePath(string $model, string $type): string
    {
        switch($type) {
            case 'model':
                return app_path('Models/').$model.'.php';
            case 'migration':
                $migrationAction = new GetMigrationName();

                return base_path('database/migrations/'.$migrationAction->execute($model));
        }
    }

    public function getSourceFile(string $model, string $type): string
    {
        return $this->getStubContents($this->getStubPath($type), $this->getStubVariables($model, $type));
    }

    public function getStubContents($stub, $stubVariables = []): string
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('{{ '.$search.' }}', $replace, $contents);
        }

        return $contents;
    }

    public function getStubPath(string $type): string
    {
        switch ($type) {
            case 'model':
                return __DIR__.'/../stubs/historization-model.stub';
            case 'migration':
                return __DIR__.'/../stubs/historization-migration.stub';
        }
    }

    public function getStubVariables(string $model, string $type)
    {
        switch ($type) {
            case 'model':
                return [
                    'CLASS_NAME' => $model,
                    'CASTS' => $this->getCastsAttribute($model),
                    'RELATED_MODEL' => $this->currentOriginalModel,
                    'RELATIONSHIP_NAME' => lcfirst($this->currentOriginalModel).'s',
                ];
            case 'migration':
                $name = $this->historizeParams[$model];
                $type = $this->modelMigrationColumns[$name];

                return [
                    'TABLE' => GetTableName::execute($model),
                    'COLUMNS' => "\t\t\t\$table->".$type."('previous_".$name."');\n\t\t\t\$table->".$type."('new_".$name."');",
                    'RELATED_MODEL' => $this->currentOriginalModel,
                ];
        }
    }

    protected function getCastsAttribute(string $model): string
    {
        $name = $this->historizeParams[$model];
        $type = $this->modelMigrationColumns[$name];

        switch($type) {
            case 'boolean':
                return "\tprotected \$dates = [\n\t\t'created_at',\n\t];\n\tprotected \$casts = [\n\t\t'".$name."' => 'boolean',";
            case 'date':
                return "\tprotected \$dates = [\n\t\t'created_at',\n\t\t'".$name."',\n\t];";
            case 'timestamp':
                return "\tprotected \$dates = [\n\t\t'created_at',\n\t\t'".$name."',\n\t];";
            case 'timestampTz':
                return "\tprotected \$dates = [\n\t\t'created_at',\n\t\t'".$name."',\n\t];";
            default:
                return "\tprotected \$dates = [\n\t\t'created_at',\n\t];";
        }
    }
}
