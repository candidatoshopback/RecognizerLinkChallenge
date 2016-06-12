<?php

namespace App\Models;

use ShopBack\Mongo\Model as Model;

/**
 * Class Link
 * @package App\Models
 */
class Link extends Model
{
    /** @var string $collection */
    protected $collection = 'Link';
    /** @var string $primaryKey */
    protected $primaryKey = '_id';
}
