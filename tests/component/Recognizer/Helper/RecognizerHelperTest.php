<?php

use ShopBack\Recognizer\Helper\RecognizerHelper;

class RecognizerHelperTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testHost()
    {
        $recognizerHelper = new RecognizerHelper();
        $host = $recognizerHelper->regexHost('http://www.lojadojoao.com.br/p/16599221');
        $this->assertTrue( $host == 'www.lojadojoao.com.br' );
    }

    public function testWords()
    {
        $recognizerHelper = new RecognizerHelper();
        $words = $recognizerHelper->regexWords('http://www.lojadojoao.com.br/p/16599221');
        $this->assertTrue( $words == 'p 16599221' );

        $words = $recognizerHelper->regexWords('http://www.lojadamaria.com.br/perfume-the-one-sport-masculino-edt?utm_source=ShopBack');
        $this->assertTrue( $words == 'perfume the one sport masculino edt utm_source ShopBack');
    }
}
