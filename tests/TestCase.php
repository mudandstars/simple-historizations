<?php

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Mudandstars\HistorizeModelChanges\HMCServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
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

        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('string');
            $table->integer('integer')->nullable();
            $table->boolean('boolean')->nullable();
            $table->date('date')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->timestampTz('timestampTz')->nullable();
            $table->timestamps();
        });
    }

    protected function getPackageProviders($app)
    {
        return [HMCServiceProvider::class];
    }

    public function createModelThatUsesTrait(): void
    {
        $path = app_path('Models/TraitTestModel.php');

        $contents = file_get_contents(__DIR__.'/../src/stubs/test-model.stub');

        file_put_contents($path, $contents);
    }
}
