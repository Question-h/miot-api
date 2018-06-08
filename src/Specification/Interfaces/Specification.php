<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-7
 * Time: 下午5:53
 */
namespace Yeelight\Specification\Interfaces;

interface Specification
{
    public function __construct($context);

    public function toContext();

    public function toCollection();

    public function toArray();

    public function toJson();
}