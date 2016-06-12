<?php

use ShopBack\Mongo\ConnectionHandler;
use ShopBack\Recognizer\Manager\RecognizerLinkManager;
use ShopBack\XmlImporter\Manager\XmlImporterManager;
use App\Models\Client;
use App\Models\Product;
use App\Models\Link;

class RecognizerLinkManagerTest extends TestCase
{
    private $client;

    public function clientLinkSetUp($domain,$xml)
    {
        $this->client = $this->getEntityFactory()->createClient($domain,$xml);

        $xmlImporterManager = new XmlImporterManager($this->client->_id);
        $xmlImporterManager->import();
    }
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        
        parent::tearDown();
        ConnectionHandler::getInstance()->getConnection($this->client->db)->drop(); 
        $this->client->getConnection()->drop();
    }

   /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 2
     */
    public function testIRecognizeInvalidArgumentException()
    {
        $this->client = $this->getEntityFactory()->createClient('www.lojadojoao.com.br','tests/resources/lojadojoao.xml');
        $recognizerLinkManager = new RecognizerLinkManager();
        $recognizerLinkManager->recognize();
    }

    public function testRecognizerLojaDoJoao()
    {

        $this->clientLinkSetUp('www.lojadojoao.com.br','tests/resources/lojadojoao.xml');

        $urls = array(
            array('url' => 'http://www.lojadojoao.com.br/p/16599221', 'value' => '16599221'),
            array('url' => 'http://www.lojadojoao.com.br/', 'value' => null),
            array('url' => 'http://www.lojadojoao.com.br/produto-de-teste-1-16599221', 'value' => '16599221'),
            array('url' => 'http://www.lojadojoao.com.br/categoria-teste', 'value' => null),
            array('url' => 'http://www.lojadojoao.com.br/search/helloword', 'value' => null),
            array('url' => 'http://www.lojadojoao.com.br/search/produto-de-teste-1-16599221?utm_teste=testando', 'value' => '16599221')
        );
        $recognizerLinkManager = new RecognizerLinkManager();

        foreach ($urls as $info) {
            $recognizerLinkManager->setUrl($info['url']);    
            $this->assertTrue( $recognizerLinkManager->recognize() == $info['value'], 'fail: '.$info['url']);
        }
    }


    public function testRecognizerLojaDaMaria()
    {
        $this->clientLinkSetUp('www.lojadamaria.com.br','tests/resources/lojadamaria.xml');
        
        $recognizerLinkManager = new RecognizerLinkManager();

        $urls = array(
            array('url' => 'http://www.lojadamaria.com.br/perfume-the-one-sport-masculino-edt/t/2/campanha_id/+752+', 'value' => '12345'),
            array('url' => 'http://www.lojadamaria.com.br/perfume-the-one-sport-masculino-edt?utm_source=ShopBack', 'value' => '12345'),
            array('url' => 'http://www.lojadamaria.com.br/search/helloword', 'value' => null),
            array('url' => 'http://www.lojadamaria.com.br/categoria-legais', 'value' => null),
            array('url' => 'http://www.lojadamaria.com.br/perfume-the-one-sport-masculino-edt', 'value' => '12345')
        );

        foreach ($urls as $info) {
            $recognizerLinkManager->setUrl($info['url']);    
            $this->assertTrue( $recognizerLinkManager->recognize() == $info['value'], 'fail: '.$info['url']);
        }
    }


    public function testRecognizerLojaDoZe()
    {
        $this->clientLinkSetUp('www.lojadoze.com.br','tests/resources/lojadoze.xml');

        $recognizerLinkManager = new RecognizerLinkManager();

        $urls = array(
            array('url' => 'http://www.lojadoze.com.br/p/chapeu-caipira-de-palha-desfiado/campanha_id/34', 'value' => '8595'),
            array('url' => 'http://www.lojadoze.com.br/chapeu-caipira-de-palha-desfiado', 'value' => '8595'),
            array('url' => 'http://www.lojadoze.com.br/home', 'value' => null),
            array('url' => 'http://www.lojadoze.com.br/categoria-teste', 'value' => null),
            array('url' => 'http://www.lojadoze.com.br/chapeu-caipira-de-palha-desfiado?google', 'value' => '8595')
        );

        foreach ($urls as $info) {
            $recognizerLinkManager->setUrl($info['url']);    
            $this->assertTrue( $recognizerLinkManager->recognize() == $info['value'], 'fail: '.$info['url']);
        }
    }
}
