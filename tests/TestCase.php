<?php

namespace Tests;

use Mudandstars\SimpleHistorizations\Actions\GetMigrationNameAction;
use Mudandstars\SimpleHistorizations\HMCServiceProvider;
use Mudandstars\SimpleHistorizations\Services\HistorizeParamsService;
use Mudandstars\SimpleHistorizations\Services\MigrationColumnsService;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public $modelName = 'TraitTestModel';

    public function getModelName(): string
    {
        return $this->modelName;
    }

    public function getTestModelMigrationColumns(): array
    {
        $getMigractionColumnsService = new MigrationColumnsService();

        $migrationName = GetMigrationNameAction::execute($this->getModelName());
        $migrationPath = base_path('database/migrations/'.$migrationName);

        $migrationColumns = $getMigractionColumnsService->getArray($migrationPath);

        return $migrationColumns;
    }

    public function getTestModelHistorizeParams(): array
    {
        $historizeParamsService = new HistorizeParamsService();

        $historizeParams = $historizeParamsService->getArray(app_path('Models/'.$this->getModelName().'.php'));

        return $historizeParams;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->createModelThatUsesTrait();
    }

    protected function tearDown(): void
    {
        $modelsPath = app_path('Models/');
        $migrationsPath = base_path('database/migrations/');

        $this->removeFilesFromDirectory($modelsPath);
        $this->removeFilesFromDirectory($migrationsPath);
    }

    protected function removeFilesFromDirectory(string $directory): void
    {
        foreach (scandir($directory) as $file) {
            if (! str_contains($file, '.php')) {
                continue;
            }

            unlink($directory.$file);
        }
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [HMCServiceProvider::class];
    }

    private function createModelThatUsesTrait(): void
    {
        $path = app_path('Models/'.$this->modelName.'.php');

        $contents = file_get_contents(__DIR__.'/../src/stubs/test-model.stub');

        file_put_contents($path, $contents);

        $this->createCorrespondingModelMigration($this->modelName);
    }

    private function createCorrespondingModelMigration(string $model): void
    {
        $correspondingMigrationPath = __DIR__.'/../database/migrations/create_trait_test_models_table.php';

        $contents = file_get_contents($correspondingMigrationPath);

        $newPath = base_path('database/migrations/'.GetMigrationNameAction::execute($model));

        file_put_contents($newPath, $contents);
    }
}
