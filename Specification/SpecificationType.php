<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-6
 * Time: 下午6:25
 */
namespace Yeelight\Specification;

use Yeelight\Exception\SpecificationErrorException;
use Yeelight\Validator\Urn;

abstract class SpecificationType implements ISpecificationType
{
    public function __construct($urn)
    {
        if (!Urn::validate($urn)) {
            throw new SpecificationErrorException('Invalid URN!');
        }
    }

    public function getType()
    {

    }

    public function getDescription()
    {

    }

    public function setType($urn)
    {

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