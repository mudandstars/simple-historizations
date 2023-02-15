<?php

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Mudandstars\HistorizeModelChanges\HMCServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
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
            $table->string('test');
            $table->timestamps();
        });
    }

    protected function getPackageProviders($app)
    {
        return [HMCServiceProvider::class];
    }
}
