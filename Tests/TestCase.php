<?php

namespace Dcblogdev\PackageName\Tests;

use Dcblogdev\PackageName\PackageNameServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            PackageNameServiceProvider::class,
        ];
    }
}
