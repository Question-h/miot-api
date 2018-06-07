<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-6
 * Time: 下午5:58
 */

namespace Yeelight\Specification;

use Yeelight\Exception\SpecificationErrorException;

class Urn
{
    /**
     * 符合 RFC 2141 的 URN正则规则
     */
    const URN_REGEXP = '/^urn:[a-z0-9][a-z0-9-]{1,31}:([a-z0-9()+,-.:=@;$_!*\']|%(0[1-9a-f]|[1-9a-f][0-9a-f]))+$/i';

    /**
     * 分隔符
     *
     * @var string
     */
    private $delimiter = ':';

    /**
     * 原始urn
     *
     * @var $original
     */
    private $original;

    /**
     * 符合 RFC 2141 和 小米规范的 URN
     * @var $expression
     */
    private $expression;

    /**
     * 第一个字段必须为urn，否则视为非法urn
     *
     * @var $urn
     */
    private $urn = 'urn';

    /**
     * 如果是小米定义的规范为miot-spec
     * 蓝牙联盟定义的规范为bluetooth-spec
     *
     * @var $namespace
     */
    private $namespace = 'miot-spec-v2';

    /**
     * SpecificationType (类型，简写为: type)
     * 只能是如下几个:
     *
     * property
     * action
     * event
     * service
     * device
     *
     * @var $type
     */
    private $type = 'property';

    /**
     * 有意义的单词或单词组合(小写字母)
     * 多个单词用"-"间隔，比如：
     *
     * temperature
     * current-temperature
     * device-name
     * battery-level
     *
     * @var $name
     */
    private $name;

    /**
     * 16进制字符串，使用UUID前8个字符，如：
     *
     * 00002A06
     * 00002A00
     *
     * @var $value
      */
    private $value;

    /**
     * 厂家+产品代号 (这个字段只有在设备实例定义里出现)
     * 有意义的单词或单词组合(小写字母)，用"-"间隔，比如：
     *
     * philips-moonlight
     * yeelink-c300
     * zhimi-vv
     * benz-c63
     *
     * @var  $vendor_product
     */
    private $vendor_product;

    /**
     * 版本号，只能是数字 (这个字段只有在设备实例定义里出现)
     * 如: 1, 2, 3
     *
     * @var $version
     */
    private $version;

    public function __construct($urn)
    {
        if (!$this->validate($urn)) {
            throw new SpecificationErrorException('Invalid URN!');
        }

        $this->original = $urn;

        $this->_parse();
    }

    /**
     * Validate a URN according to RFC 2141.
     *
     * @param $urn
     * @return TRUE when the URN is valid, FALSE when invalid
     * @internal param the $urn URN to validate
     */
    private function validate($urn)
    {
        return (bool) preg_match(self::URN_REGEXP, $urn);
    }

    /**
     * @return mixed
     */
    public function getUrn()
    {
        return $this->urn;
    }

    /**
     * @param mixed $urn
     */
    public function setUrn($urn)
    {
        $this->urn = $urn;
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getVendorProduct()
    {
        return $this->vendor_product;
    }

    /**
     * @param mixed $vendor_product
     */
    public function setVendorProduct($vendor_product)
    {
        $this->vendor_product = $vendor_product;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     *
     * @return string
     */
    private function setExpression()
    {
        $this->expression = '';

        if ($this->urn) {
            $this->expression .= $this->urn;
        }

        if ($this->namespace) {
            $this->expression .= $this->delimiter . $this->namespace;
        }

        if ($this->type) {
            $this->expression .= $this->delimiter . $this->type;
        }

        if ($this->name) {
            $this->expression .= $this->delimiter . $this->name;
        }

        if ($this->value) {
            $this->expression .= $this->delimiter . $this->value;
        }

        if ($this->vendor_product) {
            $this->expression .= $this->delimiter . $this->vendor_product;
        }

        if ($this->version) {
            $this->expression .= $this->delimiter . $this->version;
        }

        return $this->expression;
    }

    private function _parse()
    {


        $this->setExpression();

        return $this->expression;
    }
}