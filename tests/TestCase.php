<?php

namespace Tests;

use Mudandstars\HistorizeModelChanges\Actions\GetMigrationName;
use Mudandstars\HistorizeModelChanges\HMCServiceProvider;
use Mudandstars\HistorizeModelChanges\Services\GetHistorizeParams;
use Mudandstars\HistorizeModelChanges\Services\GetMigrationColumns;
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
        $migrationAction = new GetMigrationName();
        $getMigractionColumnsService = new GetMigrationColumns();

        $migrationName = $migrationAction->execute($this->getModelName());
        $migrationPath = base_path('database/migrations/'.$migrationName);

        $migrationColumns = $getMigractionColumnsService->getArray($migrationPath);

        return $migrationColumns;
    }

    public function getTestModelHistorizeParams(): array
    {
        $historizeParamsService = new GetHistorizeParams();

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
        $modelPath = app_path('Models/'.$this->modelName.'.php');

        //TODO destroy model and migration file
        //TODO destroy all files in the test-env folder 'app\models' and 'database\migrations'
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
        // $this->createDateHistorizationModel();
    }

    private function createDateHistorizationModel(): void
    {
        $path = app_path('Models/DateHistorization.php');

        $contents = file_get_contents(__DIR__.'/../src/Models/DateHistorization.php');

        file_put_contents($path, $contents);

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
