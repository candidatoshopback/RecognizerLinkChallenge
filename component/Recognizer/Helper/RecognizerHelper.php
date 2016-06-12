<?php

namespace ShopBack\Recognizer\Helper;

/**
 * Class RecognizerHelper
 * @package ShopBack\Recognizer\Helper
 */
class RecognizerHelper
{
    /**
     * @param string $resource
     * @return string
     */
    public function regexHost($resource)
    {
        $result = '';
        $matches = array();
        preg_match("/(?<=\/\/)(.+?)(?=\/|\?)/", $resource, $matches);
        if ($matches) {
            $result = $matches[0];
        } 
        return $result;
    }

    /**
     * @param string $resource
     * @return string
     */
    public function regexWords($resource)
    {
        //removing www.somesite.com.br
        $resourceSplit = preg_split("/(?<=\/\/)(.+?)(?=\/|\?)/", $resource);
        $resource = $resourceSplit[1];

        $matches = array();
        preg_match_all("/(\w+)/", $resource, $matches);
        return implode(" ", $matches[0]);

    }
}