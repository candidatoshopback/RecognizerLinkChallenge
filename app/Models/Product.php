<?php

namespace App\Models;

use ShopBack\Mongo\Model as Model;

/**
 * Class Product
 * @package App\Models
 */
class Product extends Model
{
    /** @var string $collection */
    protected $collection = 'Product';
    /** @var string $primaryKey */
    protected $primaryKey = '_id';
}
