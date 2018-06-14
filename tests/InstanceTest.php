<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-14
 * Time: 上午10:52
 */

use MiotApi\Contract\Instance\Instance;

class InstanceTest extends PHPUnit_Framework_TestCase
{
    private $instance;

    /**
     * @throws \MiotApi\Exception\SpecificationErrorException
     */
    public function setUp()
    {
        $urn = 'urn:miot-spec-v2:device:light:0000A001:philips-moonlight:1';

        $this->instance = new Instance($urn);
    }

    public function tearDown()
    {
        $this->instance = null;
    }


    public function testInit()
    {
        var_dump($this->instance);
    }

    public function testGetSpecification()
    {

    }
}
