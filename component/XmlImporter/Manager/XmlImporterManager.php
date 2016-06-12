<?php

namespace ShopBack\XmlImporter\Manager;

use App\Models\Client;
use App\Models\Product;
use App\Models\Link;
use DB;
use \InvalidArgumentException;
use ShopBack\Exception\NotFoundException;

/**
 * Class XmlImporterManager
 * @package ShopBack\XmlImporter\Manager
 */
class XmlImporterManager
{
    /**
     * @var Client $client
     */
    private $client;

    /**
     * Create a new RecognizerManager instance.
     *
     * @param string $client_id
     *
     * @return void
     */
    public function __construct($client_id)
    {
        $this->client = new Client();
        $this->client = $this->client->find($client_id);
        
        if (empty($this->client)) {
            throw new NotFoundException("Client {$client_id} not found", 3);
            
        }
    }

    /**
     * @return \SimpleXMLElement|string
     */
    private function loadXml()
    {
        if (!is_file($this->client->xml)) {
            throw new InvalidArgumentException("invalid xml path", 1);
        }

        $xml = file_get_contents($this->client->xml);
        $xml = simplexml_load_string($xml);
        return $xml;
    }

    /**
     *
     */
    public function import()
    {
        $xml = $this->loadXml();
        $product = new Product($this->client->db);
        $product->product_id = $xml->id->__toString();
        $product->title = $xml->title->__toString();
        $product->price = floatval($xml->price->__toString());
        $product->save();
        $product->collection()->createIndex(array("title" => "text", "product_id" => "text"));

        $link = new Link($this->client->db);
        $link->url = $xml->link->__toString();
        $link->product_id = $product->product_id;
        $link->save();
        $link->collection()->createIndex(array("url" => "text"));
    }
}