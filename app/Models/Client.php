<?php

namespace App\Models;

use ShopBack\Mongo\Model as Model;
use ShopBack\Util\Util as Util;

/**
 * Class Client
 * @package App\Models
 */
class Client extends Model
{
    /** @var string $collection */
    protected $collection = 'Client';
    /** @var string  $primaryKey*/
    protected $primaryKey = '_id';

    /**
     * @param array $options
     * @return bool
     */
    public function save()
    {
    	$this->attributes['db'] = Util::slug(str_replace(".","-",$this->domain));
    	return parent::save();
    }
}
