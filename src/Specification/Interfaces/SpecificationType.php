<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-6
 * Time: 下午6:09
 */

namespace Yeelight\Specification\Interfaces;

interface SpecificationType
{
    public function __construct(Urn $urn);

    public function getType();

    public function setType(Urn $urn);

    public function getDescription();

    public function setDescription($description);

    public function getInstance();

    public function setInstance(Instance $context);
}