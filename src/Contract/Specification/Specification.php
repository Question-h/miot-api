<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-7
 * Time: 下午5:49
 */
namespace Yeelight\Contract\Specification;

use Yeelight\Contract\Interfaces\Specification as SpecificationInterface;
use Yeelight\Contract\Collection\Collection;

abstract class Specification implements SpecificationInterface
{
    private $context;

    private $collection;

    public function __construct($context)
    {
        $this->context = $context;
        $this->init();
    }

    protected function init()
    {
        $this->collection = new Collection(json_decode($this->context, true));
    }

    public function toContext()
    {
        return $this->context;
    }

    public function toCollection()
    {
        return $this->collection;
    }

    public function toJson()
    {
        return $this->collection->toJson();
    }

    public function toArray()
    {
        return $this->collection->toArray();
    }

    public function __get($key)
    {
        return $this->collection->offsetGet($key);
    }

    /**
     * Proxy a method call onto the collection items.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->collection->{$method}(...$parameters);
    }
}