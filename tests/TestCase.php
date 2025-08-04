<?php

namespace Akbarjimi\ExcelImporter\Tests;

use Akbarjimi\ExcelImporter\ExcelImporterServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['queue.default' => 'sync']);
    }

    /**
     * Load your service provider.
     */
    protected function getPackageProviders($app): array
    {
        return [
            ExcelImporterServiceProvider::class,
            \Maatwebsite\Excel\ExcelServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite', 'database' => ':memory:',
        ]);
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../src/database/migrations');
    }
}
