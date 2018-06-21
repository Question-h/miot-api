<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-19
 * Time: 上午10:29.
 */
use MiotApi\Api\Api;

class ApiTest extends PHPUnit_Framework_TestCase
{
    private $api;

    public function setUp()
    {
        $this->api = new Api(getenv('appId'), getenv('accessToken'));
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
        $did = 'M1GAxtaW9A0LXNwZWMtdjIVgoAFGBB5ZWVsaW5rLWNlaWxpbmc0FRQYCDYyMzExNjc1FYQIAA';
        $type = 'urn:miot-spec-v2:device:light:0000A001:yeelink-ceiling4:1';
        $data = [
            'on'                => true,
            'brightness'        => [99, 50],
            'color-temperature' => [3100, 5000],
            'color'             => 2777215,
        ];
        $requestInfo = $this->api->setPropertyGraceful($did, $type, $data);

        $this->assertEquals(0, $requestInfo['properties'][0]['status']);

        $getInfo = $this->api->properties('M1GAxtaW9A0LXNwZWMtdjIVgoAFGBB5ZWVsaW5rLWNlaWxpbmc0FRQYCDYyMzExNjc1FYQIAA.2.2');

        $this->assertEquals(99, $getInfo['properties'][0]['value']);
    }

    public function testSetPropertiesGraceful()
    {
        $data = [
            'M1GAxtaW9A0LXNwZWMtdjIVgoAFGA55ZWVsaW5rLWNvbG9AyMRUUGAg0NTk2NTYwNRVoAA' => [
                'type' => 'urn:miot-spec-v2:device:light:0000A001:yeelink-color1:1',
                'data' => [
                    'on'                => true,
                    'brightness'        => 99,
                    'color-temperature' => 2100,
                    'color'             => 2777215,
                ],
            ],
            'M1GAxtaW9A0LXNwZWMtdjIVgoAFGAt5ZWVsaW5rLWN0MhUUGAg4NzEzMDQyMhWcCAA' => [
                'type' => 'urn:miot-spec-v2:device:light:0000A001:yeelink-ct2:1',
                'data' => [
                    'on'                => true,
                    'brightness'        => 50,
                    'color-temperature' => 3500,
                ],
            ],
        ];
        $requestInfo = $this->api->setPropertiesGraceful($data);

        $this->assertEquals(0, $requestInfo['properties'][0]['status']);

        $getInfo = $this->api->properties('M1GAxtaW9A0LXNwZWMtdjIVgoAFGA55ZWVsaW5rLWNvbG9AyMRUUGAg0NTk2NTYwNRVoAA.2.2');
        $this->assertEquals(99, $getInfo['properties'][0]['value']);
    }

    public function testGetPropertyGraceful()
    {
        $did = 'M1GAxtaW9A0LXNwZWMtdjIVgoAFGA55ZWVsaW5rLWNvbG9AyMRUUGAg0NTk2NTYwNRVoAA';
        $type = 'urn:miot-spec-v2:device:light:0000A001:yeelink-color1:1';
        // $data = []; // 为空时，获取所有可读属性
        $data = [
            'on',
            'brightness',
            'color-temperature',
            'color',
        ];
        $requestInfo = $this->api->getPropertyGraceful($did, $type, $data);

        $this->assertArrayHasKey('M1GAxtaW9A0LXNwZWMtdjIVgoAFGA55ZWVsaW5rLWNvbG9AyMRUUGAg0NTk2NTYwNRVoAA', $requestInfo);
    }

    public function testGetPropertiesGraceful()
    {
        $data = [
            'M1GAxtaW9A0LXNwZWMtdjIVgoAFGA55ZWVsaW5rLWNvbG9AyMRUUGAg0NTk2NTYwNRVoAA' => [
                'type' => 'urn:miot-spec-v2:device:light:0000A001:yeelink-color1:1',
                'data' => [
                    'on',
                    'brightness',
                    'color-temperature',
                    'color',
                ],
            ],
            'M1GAxtaW9A0LXNwZWMtdjIVgoAFGAt5ZWVsaW5rLWN0MhUUGAg4NzEzMDQyMhWcCAA' => [
                'type' => 'urn:miot-spec-v2:device:light:0000A001:yeelink-ct2:1',
                'data' => [], // 为空时，获取所有可读属性
            ],
        ];
        $requestInfo = $this->api->getPropertiesGraceful($data);
        $this->assertArrayHasKey('M1GAxtaW9A0LXNwZWMtdjIVgoAFGA55ZWVsaW5rLWNvbG9AyMRUUGAg0NTk2NTYwNRVoAA', $requestInfo);
        $this->assertArrayHasKey('M1GAxtaW9A0LXNwZWMtdjIVgoAFGAt5ZWVsaW5rLWN0MhUUGAg4NzEzMDQyMhWcCAA', $requestInfo);
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
