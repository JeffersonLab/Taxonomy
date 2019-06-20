<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 4/12/18
 * Time: 10:05 AM
 */

namespace Tests;

use JLab\Taxonomy\Vocabulary;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Jlab\Taxonomy\TaxonomyServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [TaxonomyServiceProvider::class];
    }

    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Vocabulary' => Vocabulary::class,
        ];
    }


    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');

    }

}