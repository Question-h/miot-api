<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-6
 * Time: 下午6:25
 */

namespace MiotApi\Contract\Instance;

use MiotApi\Contract\Specification\PropertySpecification;
use MiotApi\Contract\Urn;
use MiotApi\Util\Collection\Collection;

class Property extends PropertySpecification
{
    protected $data;

    /**
     * 实例ID(Instance ID，简称iid)
     * @var
     */
    protected $iid;

    /**
     * type对象
     * @var
     */
    protected $specification;

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->collection = new Collection($this->data);

        $this->init();
    }

    public function init()
    {
        parent::init();

        $this->iid = $this->collection->get('iid');
        $this->urn = new Urn($this->collection->get('type'));

        $this->specification = new PropertySpecification($this->urn->getBaseUrn());
    }

    public function getIid()
    {
        return $this->iid;
    }

    /**
     * 验证给定的值是否 符合 format
     * @param $value
     * @return bool
     */
    public function verify($value)
    {
        if ($this->has('value-range')) {
            $valueRange = $this->get('value-range');
            if ($value > $valueRange[1] || $value < $valueRange[0]) {
                return false;
            }
        }

        switch ($this->format) {
            case 'bool':
                return in_array($value, [
                    true,
                    false,
                    1,
                    0
                ]);
                break;
            case 'uint8':
            case 'uint16':
            case 'uint32':
            case 'int8':
            case 'int16':
            case 'int32':
            case 'int64':
                return is_integer($value);
                break;
            case 'float':
                return is_float($value);
                break;
            case 'string':
                return is_string($value);
                break;
        }
    }

    public function getSpecification()
    {
        return $this->specification;
    }

    public function getSpecificationContext()
    {
        return $this->specification->toContext();
    }
}