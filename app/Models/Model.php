<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * Class Model
 * @package App\Models
 * @mixin Builder
 */
abstract class Model extends BaseModel
{
    use ExtraMethodTrait;

    /**
     * Postgresql time format
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';
}
