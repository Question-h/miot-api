<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-6
 * Time: 下午6:09
 */

namespace Yeelight\Specification;

interface ISpecificationType
{
    public function __construct($urn);

    public function getType();

    public function getDescription();

    public function setType($urn);

    public function setDescription($description);

    public function context();

    public function parse($context);
}