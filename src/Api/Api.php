<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-14
 * Time: 下午4:39
 */

namespace MiotApi\Api;

use MiotApi\Contract\Instance\Instance;
use MiotApi\Exception\ApiErrorException;

/**
 * 更方便的API调用
 *
 * Class Api
 * @package MiotApi\Api
 */
class Api extends BaseApi
{
    /**
     * 一次性获取到包含了 serialNumber （原did）的设备列表
     * @return array|mixed
     */
    public function devicesList()
    {
        $devicesList = [];
        $devices = $this->devices();

        if (isset($devices['devices'])) {
            if (!empty($devices['devices'])) {
                foreach ($devices['devices'] as $device) {
                    $dids[] = $device['did'];
                    $device['serialNumber'] = null;
                    $devicesList[$device['did']] = $device;
                }

                $deviceInformations = $this->deviceInformation($dids);

                if (isset($deviceInformations['device-information'])) {
                    foreach ($deviceInformations['device-information'] as $deviceInformation) {
                        if (isset($devicesList[$deviceInformation['id']])) {
                            $devicesList[$deviceInformation['id']]['serialNumber'] = $deviceInformation['serialNumber'];
                        }
                    }
                }

                return array_values($devicesList);
            } else {
                // 没有设备的情况
                return [];
            }
        } else {
            // 获取设备出错
            return $devices;
        }
    }

    /**
     * 按照名称设置属性
     *
     * @param $did
     * @param $type
     * @param $data | $data = ['brightness' => 75, 'on' => true]
     * @return array|bool|mixed
     * @throws ApiErrorException
     * @throws \MiotApi\Exception\JsonException
     * @throws \MiotApi\Exception\SpecificationErrorException
     */
    public function setPropertyGraceful($did, $type, $data)
    {
        if (!empty($data)) {
            $properties = [];
            $instance = new Instance($type);
            $propertiesNodes = $instance->getPropertiesNodes();

            foreach ($data as $name => $value) {
                list($sid, $pid) = $instance->getSidPidByName($name);

                if (!$sid || !$pid) {
                    throw new ApiErrorException('Invalid property name : ' . $name);
                }

                $property = $propertiesNodes[($sid . '.' . $pid)];

                if (!$property->verify($value)) {
                    throw new ApiErrorException('Invalid property value, the property name: ' . $name);
                }

                if (!$property->canWrite()) {
                    throw new ApiErrorException('The property : ' . $name . ' does\'t has the write access!');
                }

                $properties[] = [
                    'pid' => $did . '.' . $sid . '.' . $pid,
                    'value' => $value
                ];
            }

            return $this->setProperties([
                'properties' => $properties
            ]);
        } else {
            throw new ApiErrorException('Properties data required');
        }
    }

    public function setPropertiesGraceful($data)
    {

    }

    /**
     * 根据 devicesList 方法获取到的设备列表信息 订阅设备属性变化
     *
     * @param $devices
     * @param $receiverUrl
     * @return array|bool|mixed
     * @throws \MiotApi\Exception\SpecificationErrorException
     */
    public function subscriptByDevices($devices, $receiverUrl)
    {
        $subscriptProperties = $this->getPropertiesByDevices($devices, ['notify']);

        return $this->subscript($subscriptProperties, $receiverUrl);
    }

    /**
     * 根据 devicesList 方法获取到的设备列表信息 退订设备属性变化
     *
     * @param $devices
     * @return array|bool|mixed
     * @throws \MiotApi\Exception\SpecificationErrorException
     */
    public function unSubscriptByDevices($devices)
    {
        $subscriptProperties = $this->getPropertiesByDevices($devices, ['notify']);

        return $this->unSubscript($subscriptProperties);
    }

    /**
     * 根据设备列表和 access列表 获取对于访问方式的属性
     *
     * @param $devices
     * @param array $access | ['read'] ['read', 'notify'] ['read', 'write', 'notify']
     * @return array|bool
     * @throws \MiotApi\Exception\SpecificationErrorException
     */
    protected function getPropertiesByDevices($devices, $access = [])
    {
        try {
            $properties = [];
            if (!empty($devices) && !isset($devices['status'])) {
                foreach ($devices as $dindex => $device) {
                    $instance = new Instance($device['type']);
                    $propertiesNodes = $instance->getPropertiesNodes();
                    if (!empty($propertiesNodes)) {
                        foreach ($propertiesNodes as $index => $property) {
                            if (in_array('read', $access)) {
                                if ($property->canRead()) {
                                    $properties[] = $device['did'] . '.' . $index;
                                }
                            }
                            if (in_array('write', $access)) {
                                if ($property->canWrite()) {
                                    $properties[] = $device['did'] . '.' . $index;
                                }
                            }
                            if (in_array('notify', $access)) {
                                if ($property->canNotify()) {
                                    $properties[] = $device['did'] . '.' . $index;
                                }
                            }
                        }
                    }
                }
            } else {
                throw new ApiErrorException('invalid devices lists');
            }

            return $properties;
        } catch (ApiErrorException $exception) {
            return false;
        }
    }
}