<?php

namespace Mudandstars\HistorizeModelChanges\Services\MakeFiles;

use Illuminate\Filesystem\Filesystem;

class MakeModelService extends MakeFileService
{
    public function __construct(Filesystem $files, $logger, string $originalModelName, string $originalModelPath)
    {
        parent::__construct($files, $logger, $originalModelName, $originalModelPath);
    }

    protected function getStubParameters(string $modelName): array
    {
        return [
            'CLASS_NAME' => $modelName,
            'CASTS' => $this->castsAttributes($modelName),
            'RELATED_MODEL' => $this->originalModelName,
            'RELATIONSHIP_NAME' => lcfirst($this->originalModelName).'s',
        ];
    }

    protected function getSourceFilePath(string $modelPath): string
    {
        return app_path('Models/').$modelPath.'.php';
    }

    protected function getStubPath(): string
    {
        return __DIR__.'/../../stubs/historization-model.stub';
    }
}
