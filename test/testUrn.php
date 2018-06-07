<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-7
 * Time: 下午3:01
 */

require_once '../vendor/autoload.php';

use Yeelight\Specification\Urn;

// 测试urn
// 参考 http://miot-spec.org/miot-spec-v2/instances
$urn = 'urn:miot-spec-v2:device:light:0000A001:yeelink-bslamp1:1';

// 实例化 urn 处理器
$urnObj = new Urn($urn);

var_dump($urnObj);

