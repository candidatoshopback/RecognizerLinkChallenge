<?php

use App\Models\Link;
use ShopBack\Mongo\ConnectionHandler;

class ModelTest extends TestCase
{
    /** @var  string $databaseNameTest */
    private $databaseNameTest = "databaseTest";

    public function setUp()
    {
        parent::setUp();
    }
    public function tearDown()
    {
        parent::tearDown();
        ConnectionHandler::getInstance()->getConnection($this->databaseNameTest)->drop();
    }

    public function testInsert()
    {
        $linkTest = $this->getEntityFactory()->createLink($this->databaseNameTest,"www.linkteste.com");
        $link = new Link($this->databaseNameTest);
        $link = $link->find(array('url' => "www.linkteste.com"));
        $this->assertNotTrue(is_null($link));
        $this->assertTrue($link->_id == $linkTest->_id);
    }

    
    public function testUpdate()
    {
        $linkTest = $this->getEntityFactory()->createLink($this->databaseNameTest,"www.linkteste.com");

        $link = new Link($this->databaseNameTest);
        $link = $link->find($linkTest->_id);

        $link->url = "www.linktesteupdate.com";
        $link->save();
        
        $link = $link->find(array('url' => "www.linktesteupdate.com"));
        
        $this->assertNotTrue(is_null($link));
        $this->assertTrue($link->_id == $linkTest->_id);

    }
    
    public function testFind()
    {
        $linkTest = $this->getEntityFactory()->createLink($this->databaseNameTest,"www.linkteste.com");
        $link = new Link($this->databaseNameTest);
        $link = $link->find($linkTest->_id);
        $this->assertNotTrue(is_null($link));
        $this->assertTrue($link->_id == $linkTest->_id);
    }


}
