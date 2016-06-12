<?php

use Test\Factory\EntityFactory;
use ShopBack\Mongo\ConnectionHandler;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /** @var  EntityFactory $entityFactory */
    protected $entityFactory;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $this->setEntityFactory(new EntityFactory());
        ConnectionHandler::setDefaultDatabase('master-test');
        return $app;
    }

    /**
     * @return EntityFactory $entityFactory
     */
    public function getEntityFactory()
    {
        return $this->entityFactory;
    }

    public function setEntityFactory($entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }
}
