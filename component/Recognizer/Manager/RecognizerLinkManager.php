<?php

namespace ShopBack\Recognizer\Manager;

use Cache;
use App\Models\Product;
use App\Models\Link;
use App\Models\Client;
use ShopBack\Recognizer\Helper\RecognizerHelper;
use ShopBack\Exception\NotFoundException;
use \InvalidArgumentException;

class RecognizerLinkManager
{
    private $recognizerHelper;
    private $url;
    private $host;
    private $client;

    /**
     * Create a new RecognizerManager instance.
     *
     * @return void
     */
    public function __construct($url = null)
    {
        $this->url = $url;
        $this->recognizerHelper = new RecognizerHelper();
    }

    /**
     * @param $url
     * 
     * @return RecognizerLinkManager $instance
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return null
     * @throws NotFoundException
     */
    public function recognize()
    {
    	if (empty($this->url)) {
    		throw new InvalidArgumentException("Invalid link parameter",2);
    	}

        $this->host = $this->recognizerHelper->regexHost($this->url);
        $this->loadClient();
        $product_id = $this->recognizeUrl();

        if (empty($product_id)) {
            $product_id = $this->autoRecognize();
        }

        return $product_id;
    }

    /**
     * @return null
     */
    private function recognizeUrl()
    {
        $product_id = null;
        if (Cache::has($this->url)) {
            $product_id = Cache::get($this->url);
        } else {
            $product_id = $this->discoveryUrl();
        }

        return $product_id;
    }

    /**
     * @return null
     */
    private function autoRecognize()
    {
        $words = $this->recognizerHelper->regexWords($this->url);
        $product_id = null;

        $product = new Product($this->client->db);
        $result = $product->find(['$text' => ['$search' => $words]]);

        if ($result) {
            $product_id = $result->product_id;
            Cache::add($this->url, $product_id, 60);
            $this->learnUrlProduct($product_id);
        } else {
            $link = new Link($this->client->db);
            $result = $link->find(['$text' => ['$search' => $words]]);
            if ($result) {
                $product_id = $result->product_id;
                Cache::add($this->url, $product_id, 60);
                $this->learnUrlProduct($product_id);
            }
        }

        return $product_id;
    }

    /**
     * @return null
     */
    private function discoveryUrl()
    {
        $product_id = null;

        $link = new Link($this->client->db);

        $product = $link->findByAttributes(array("url" => $this->url));

        if (!is_null($product)) {
            Cache::add($this->url, $product->product_id, 60);
            $product_id = $product->product_id;
        }

        return $product_id;
    }

    /**
     * @throws NotFoundException
     */
    private function loadClient()
    {
        $this->client = new Client;
        $this->client = $this->client->find(array('domain' => $this->host));
        if (is_null($this->client)) {
            throw new NotFoundException("Client {$this->host} not found", 3);
        }
    }

    /**
     * @param int $product_id
     */
    public function learnUrlProduct($product_id)
    {
        $link = new Link($this->client->db);
        $link->url = $this->url;
        $link->info = $this->recognizerHelper->regexWords($this->url);
        $link->product_id = $product_id;
        $link->save();

    }
}