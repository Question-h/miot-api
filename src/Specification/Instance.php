<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-7
 * Time: 下午5:49
 */
namespace Yeelight\Specification;

use Yeelight\Specification\Interfaces\Instance as InstanceInterface;

abstract class Instance implements InstanceInterface
{
    private $context;

    private $collection;

    public function __construct($context)
    {
        $this->context = $context;
        $this->fromContext();
    }

    private function fromContext()
    {
        $this->collection = json_decode($this->context, true);
    }

    public function toContext()
    {
        return $this->context;
    }
}