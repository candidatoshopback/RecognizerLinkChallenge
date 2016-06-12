<?php

namespace Test\Factory;

use App\Models\Client;
use App\Models\Link;
/**
 * Class EntityFactory
 * @package Test\Factory
 */
class EntityFactory
{
	public function construct()
	{}

	/**
	 * @param string $domain
	 * @param string $xml
	 * @return Client $client
	 */
	public function createClient($domain,$xml)
	{

        $client = new Client();
        $client->domain = $domain;
        $client->xml = $xml;
        $client->save();

        return $client;
	}

	/**
	 * @param string $clientDb
	 * @param $url
	 */
	public function createLink($clientDb,$url)
	{
		$link = new Link($clientDb);
		$link->url = $url;
		$link->save();
		return $link;
	}
}