<?php

namespace ShopBack\Mongo;

use MongoDB\BSON\ObjectID;
use MongoDB\Collection;
use ShopBack\Mongo\ConnectionHandler;
use \MongoId;
use \Exception;

/**
 * Class Model
 * @package ShopBack\Mongo
 */
class Model
{
    /** @var array */
    public $attributes = array();
    /** @var MongoClient $connection */
    protected $connection;
    /** @var  string $primaryKey */
    protected $primaryKey;
    /** @var  string $collection */
    protected $collection;

    protected $database;

    /**
     * Model constructor.
     * @param null $database
     */
    public function __construct($database = null)
    {
        $this->database = $database;
        $connection = ConnectionHandler::getInstance()->getConnection($database);
        if (is_null($connection)) {
            throw new Exception("Error connecting to database $database", 5);
        }
        $this->connection = $connection;
    }

    /**
     * @return \MongoDB\Client
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param $database
     */
    public function setConnection($database)
    {
        $this->connection = ConnectionHandler::getInstance()->getConnection($database);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->attributes[$name];
    }

    /**
     * @param $name
     * @param $val
     * @return $this
     */
    public function __set($name, $val)
    {
        $this->attributes[$name] = $val;
        return $this;
    }

    /**
     * @param $array
     * @return null
     */
    public function findByAttributes($array)
    {
        $res = $this->connection->{$this->collection}->findOne($array);

        if (!$res) {
            return null;
        }

        $class = "App\Models\\$this->collection";
        $obj = new $class($this->database);

        $id = $res['_id']->__toString();
        unset($res['_id']);
        $obj->attributes[$this->primaryKey] = $id;
        $obj->attributes = array_merge($obj->attributes, $res->getArrayCopy());

        return $obj;
    }

    /**
     * @param $data
     * @return null
     */
    public function find($data)
    {
        if (is_array($data)) {
            return $this->findByAttributes($data);
        } else {
            return $this->findByAttributes(array($this->primaryKey => new ObjectID($data)));
        }
    }

    /**
     * @return \MongoDB\Collection
     */
    public function collection()
    {
        return $this->connection->{$this->collection};
    }

    /**
     * @return void
     */
    public function save()
    {
        /** @var \MongoDB\Collection $collection */
        $collection = $this->collection();
        if (isset($this->attributes[$this->primaryKey])) {
            $id = new ObjectID($this->attributes[$this->primaryKey]);
            $attributes = $this->attributes;
            unset($attributes[$this->primaryKey]);
            $updateResult = $collection->updateOne(array($this->primaryKey => $id), array('$set' => $attributes));
            $this->attributes = $this->find($this->attributes[$this->primaryKey])->attributes;
        } else {
            $insertResult = $collection->insertOne($this->attributes);
            $insertedId = $insertResult->getInsertedId()->__toString();
            $this->attributes = $this->find($insertedId)->attributes;
        }
    }
}