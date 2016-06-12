<?php

use ShopBack\Mongo\ConnectionHandler;

class ConnectionHandlerTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */

    public function testConnection()
    {
        $connection = ConnectionHandler::getInstance()->getConnection();
        $this->assertTrue(ConnectionHandler::getInstance() instanceof ConnectionHandler );
        $this->assertTrue($connection instanceof MongoDB\Database);
    }

    public function testDynamicConnection()
    {
        $connection = ConnectionHandler::getInstance()->getConnection("dynamic");
        $this->assertTrue(ConnectionHandler::getInstance() instanceof ConnectionHandler );
        $this->assertTrue($connection instanceof MongoDB\Database);
    }
}
