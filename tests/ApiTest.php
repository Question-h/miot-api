<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-19
 * Time: 上午10:29
 */

use MiotApi\Api\Api;

class ApiTest extends PHPUnit_Framework_TestCase
{

    private $api;

    public function setUp()
    {
        $this->api = new Api('appId', 'accessToken');
    }

    public function tearDown()
    {
        $this->api = null;
    }

    public function testDevicesList()
    {
        $this->assertNotEmpty($this->api->devicesList());
        $this->assertArrayNotHasKey('status', $this->api->devicesList());
    }
}
