<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-7
 * Time: 下午5:53
 */
namespace Yeelight\Specification\Interfaces;

interface Urn
{
    public function __construct($urn);

    public function getExpression();
}