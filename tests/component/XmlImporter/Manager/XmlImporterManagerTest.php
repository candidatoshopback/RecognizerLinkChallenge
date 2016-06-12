<?php

use ShopBack\XmlImporter\Manager\XmlImporterManager;
use ShopBack\Mongo\ConnectionHandler;
use App\Models\Product;
use App\Models\Client;

class XmlImporterManagerTest extends TestCase
{
    private $client;
    public function setUp()
    {
        parent::setUp();
        $this->client = $this->getEntityFactory()->createClient(
            'www.lojadojoao.com.br',
            'tests/resources/lojadojoao.xml'
        );
    }


    public function tearDown()
    {
        parent::tearDown();
        ConnectionHandler::getInstance()->getConnection($this->client->db)->drop(); 
        $this->client->getConnection()->drop();
    }
    
    public function testImport()
    {
        $xmlImporterManager = new XmlImporterManager($this->client->_id);
        $xmlImporterManager->import();

        $product = new Product($this->client->db);
        $product = $product->findByAttributes(array('product_id' => "16599221"));

        $this->assertTrue( !is_null($product) );
        $this->assertTrue( $product->product_id == '16599221' );
    }

   /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 1
     */
    public function testImportInvalidArgumentException()
    {
        $this->client->xml = "not_a_file";
        $this->client->save();
        $xmlImporterManager = new XmlImporterManager($this->client->_id);
        $xmlImporterManager->import();
    }
    
   /**
     * @expectedException \ShopBack\Exception\NotFoundException
     * @expectedExceptionCode 3
     */
    public function testXmlImporterNotFoundException()
    {
        $xmlImporterManager = new XmlImporterManager('575dbc8a98750f35fd7fe784');
    }
}
