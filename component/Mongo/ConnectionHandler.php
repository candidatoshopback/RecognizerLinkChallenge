<?php

namespace ShopBack\Mongo;

use MongoDB\Client as MongoClient;

/**
 * Class ConnectionHandler
 * @package ShopBack\Mongo
 */
class ConnectionHandler
{
    /** @var string $defaultDatabase  */
    private static $defaultDatabase = 'master';
    /** @var ConnectionHandler $instance */
    private static $instance = null;
    /** @var array $connections */
    private static $connections = array();

    /**
     * ConnectionHandler constructor.
     */
    private function __construct()
    {
        $client = new MongoClient();
        $defaultDatabase = self::$defaultDatabase;
        self::$connections['default'] = $client->{$defaultDatabase};
    }

    public static function setDefaultDatabase($database)
    {
        self::$defaultDatabase = $database;
    }

    /**
     * @return ConnectionHandler self::$instance
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ConnectionHandler();
        }

        return self::$instance;
    }

    /**
     * @param null $connectionName
     * @return MongoClient $connection
     */
    public function getConnection($connectionName = null)
    {
        if (!$connectionName) {
            $connectionName = self::$defaultDatabase;
        }

        if (isset(self::$connections[$connectionName])) {
            return self::$connections[$connectionName];
        } else {
            $client = new MongoClient();
            self::$connections[$connectionName] = $client->{$connectionName};
        }

        $connection = self::$connections[$connectionName];

        return $connection;
    }
}