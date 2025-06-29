<?php

namespace Akbarjimi\ExcelImporter\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Akbarjimi\ExcelImporter\ExcelImporterServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * Load your service provider.
     */
    protected function getPackageProviders($app): array
    {
        return [
            ExcelImporterServiceProvider::class,
        ];
    }

    /**
     * Set up in-memory sqlite database or other config if needed.
     */
    protected function getEnvironmentSetUp($app): void
    {
        // Example: configure DB to sqlite memory for future migration tests
        // $app['config']->set('database.default', 'testbench');
        // $app['config']->set('database.connections.testbench', [
        //     'driver' => 'sqlite', 'database' => ':memory:'
        // ]);
    }
}
