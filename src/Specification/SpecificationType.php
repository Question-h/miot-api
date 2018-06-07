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

    public function __construct($urn)
    {
        $this->setType($urn);
    }

    public function getType()
    {

    }

    /**
     * 根据urn设置 type和 urn
     *
     * @param $urn
     */
    public function setType($urn)
    {
        $this->urn = new Urn($urn);
        $this->type = $this->urn->getExpression();
    }

    public function getDescription()
    {

    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getContext()
    {

    }

    public function init($context)
    {

    }
}