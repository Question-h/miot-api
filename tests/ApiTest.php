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

    public function testSetPropertyGraceful()
    {
        $did = 'M1GAxtaW9A0LXNwZWMtdjIVgoAFGA55ZWVsaW5rLWNvbG9AyMRUUGAg0NTk2NTYwNRVoAA';
        $type = "urn:miot-spec-v2:device:light:0000A001:yeelink-color1:1";
        $data = [
            'on' => true,
            'brightness' => 99,
            'color-temperature' => 2100,
            'color' => 5777215
        ];
        $requestInfo = $this->api->setPropertyGraceful($did, $type, $data);

        $this->assertEquals(0, $requestInfo['properties'][0]['status']);

        $getInfo = $this->api->properties('M1GAxtaW9A0LXNwZWMtdjIVgoAFGA55ZWVsaW5rLWNvbG9AyMRUUGAg0NTk2NTYwNRVoAA.2.2');
        $this->assertEquals(99, $getInfo['properties'][0]['value']);
    }

    public function testSubscriptByDevices()
    {
        $devices = $this->api->devicesList();
        $receiverUrl = 'https://cloud-cn.yeelight.com/';
        $requestInfo = $this->api->subscriptByDevices($devices, $receiverUrl);
        $this->assertArrayHasKey('properties', $requestInfo);
    }

    public function testUnSubscriptByDevices()
    {
        $devices = $this->api->devicesList();
        $requestInfo = $this->api->unSubscriptByDevices($devices);
        $this->assertArrayHasKey('properties', $requestInfo);
    }
}
