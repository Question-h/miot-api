<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-6
 * Time: 下午6:25
 */
namespace Yeelight\Specification;

abstract class SpecificationType implements ISpecificationType
{
    /**
     * @var SpecificationType, 简写为type
     * 必须是URN表达式
     */
    protected $type;

    protected $urn;

    public function __construct($urn)
    {

    }

    public function getType()
    {

    }

    public function getDescription()
    {

    }

    public function setType($urn)
    {
        $this->urn = new Urn($urn);
        $this->type = $this->urn->getExpression();
    }

    public function setDescription($description)
    {

    }

    public function context()
    {

    }

    public function parse($context)
    {

    }
}