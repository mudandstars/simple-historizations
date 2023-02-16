<?php

namespace Tests;

use Mudandstars\HistorizeModelChanges\Actions\GetMigrationName;
use Mudandstars\HistorizeModelChanges\HMCServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public $modelName = 'TestTraitModel';

    public function getModelName(): string
    {
        return $this->modelName;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->createModelThatUsesTrait();
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
        $path = app_path('Models/'.$this->modelName . '.php');

        $contents = file_get_contents(__DIR__.'/../src/stubs/test-model.stub');

        file_put_contents($path, $contents);

        $this->createCorrespondingModelMigration($this->modelName);
    }

    private function createCorrespondingModelMigration(string $model): void
    {
    $correspondingMigrationPath = __DIR__.'/../database/migrations/create_trait_test_models_table.php';

        $contents = file_get_contents($correspondingMigrationPath);

        $getMigrationNameService = new GetMigrationName();
        $newPath = base_path('database/migrations/'.$getMigrationNameService->execute($model));

        file_put_contents($newPath, $contents);
    }
}
