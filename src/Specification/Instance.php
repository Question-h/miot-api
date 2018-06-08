<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-6
 * Time: 下午6:25
 */
namespace Yeelight\Specification;

use Yeelight\Specification\Interfaces\Instance as InstanceInterface;
use Yeelight\Specification\Interfaces\Specification;
use Yeelight\Specification\Interfaces\Urn;

abstract class Instance implements InstanceInterface
{
    /**
     * @var Instance, 简写为type
     * 必须是URN表达式
     */
    protected $type;

    /**
     * 描述
     * 纯文本字段
     *
     * @var
     */
    protected $description;

    /**
     * 必须是符合 RFC 2141 和小米规范的 urn
     * @var
     */
    protected $urn;

    /**
     * type对象
     * @var
     */
    protected $specification;

    /**
     * http Client
     * @var
     */
    protected $httpClient;

    public function __construct(Urn $urn)
    {
        $this->setType($urn);
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * 根据urn设置 type和 urn
     *
     * @param $urn
     */
    public function setType(Urn $urn)
    {
        $this->urn = $urn;
        $this->type = $this->urn->getExpression();
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getSpecification()
    {
        return $this->specification;
    }

    public function getSpecificationContext()
    {
        if ($this->specification instanceof Specification) {
            return $this->specification->toContext();
        } else {
            return null;
        }
    }

    public function setSpecification(Specification $specification)
    {
        $this->specification = $specification;
    }
}