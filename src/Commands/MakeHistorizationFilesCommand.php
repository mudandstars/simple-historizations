<?php

namespace Mudandstars\HistorizeModelChanges\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Mudandstars\HistorizeModelChanges\Services\HistorizeParamsService;
use Mudandstars\HistorizeModelChanges\Services\MakeFiles\MakeMigrationService;
use Mudandstars\HistorizeModelChanges\Services\MakeFiles\MakeModelService;

class MakeHistorizationFilesCommand extends Command
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

    public function handle(HistorizeParamsService $historizeParamsService)
    {
        $modelsPath = app_path('/Models/');
        $traitModelsPaths = $this->traitModelsPaths($modelsPath);

        foreach ($traitModelsPaths as $modelPath) {
            $originalModelName = trim($modelPath, '.php');

            $makeMigrationService = new MakeMigrationService($this->files, $this, $originalModelName, $modelsPath.$modelPath);
            $makeModelService = new MakeModelService($this->files, $this, $originalModelName, $modelsPath.$modelPath);

            foreach (array_keys($historizeParamsService->getArray($modelsPath.$modelPath)) as $modelName) {
                $makeMigrationService->makeFile($modelName);
                $makeModelService->makeFile($modelName);
            }
        }

        return COMMAND::SUCCESS;
    }

    protected function traitModelsPaths(string $modelsPath): array
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
}
