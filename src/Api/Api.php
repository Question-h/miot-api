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
     * 按照名称获取属性
     *
     * @param $did
     * @param $type
     * @param $data | $data = ['brightness' => 75, 'on' => true]
     * @return array|bool|mixed
     * @throws ApiErrorException
     * @throws \MiotApi\Exception\JsonException
     * @throws \MiotApi\Exception\SpecificationErrorException
     */
    public function getPropertyGraceful($did, $type, $data)
    {
        $propertyData = [
            $did => [
                'type' => $type,
                'data' => $data
            ]
        ];

        return $this->getPropertiesGraceful($propertyData);
    }

    /**
     * 按照名称获取多个设备属性
     *
     * @param $data
     * $data = ['AABBCD-did' => ['type' => 'urn:miot-spec-v2:device:light:0000A001:yeelink-color1:1', data => ['brightness', 'on']]]
     * @return array|bool|mixed
     * @throws ApiErrorException
     * @throws \MiotApi\Exception\JsonException
     * @throws \MiotApi\Exception\SpecificationErrorException
     */
    public function getPropertiesGraceful($data)
    {
        if (!empty($data)) {
            $properties = [];
            $attributes = [];
            $instances = [];
            foreach ($data as $did => $datum) {
                if (isset($datum['type'])) {
                    $instance = new Instance($datum['type']);
                    $propertiesNodes = $instance->getPropertiesNodes();
                    $instances[$did] = $propertiesNodes;

                    if (!empty($datum['data'])) {
                        foreach ($datum['data'] as $name) {
                            list($sid, $pid) = $instance->getSidPidByName($name);

                            if (!$sid || !$pid) {
                                throw new ApiErrorException('Invalid property! did:' . $did . ',name: ' . $name);
                            }

                            $property = $propertiesNodes[($sid . '.' . $pid)];

                            if (!$property->canRead()) {
                                throw new ApiErrorException('The property does\'t has the read access! did:' . $did . ',name: ' . $name);
                            }

                            $properties[] = $did . '.' . $sid . '.' . $pid;
                        }
                    } else {
                        foreach ($propertiesNodes as $property) {
                            $name = $property->getUrn()->getName();
                            list($sid, $pid) = $instance->getSidPidByName($name);

                            if (!$sid || !$pid) {
                                throw new ApiErrorException('Invalid property! did:' . $did . ',name: ' . $name);
                            }

                            $property = $propertiesNodes[($sid . '.' . $pid)];

                            if (!$property->canRead()) {
                                throw new ApiErrorException('The property does\'t has the read access! did:' . $did . ',name: ' . $name);
                            }

                            $properties[] = $did . '.' . $sid . '.' . $pid;
                        }
                    }
                } else {
                    throw new ApiErrorException('Properties data and device type required');
                }
            }

            $response = $this->properties($properties);
            if (isset($response['properties']) && !empty($response['properties'])) {
                foreach ($response['properties'] as $index => $res) {
                    $pidArr = explode('.', $res['pid']);
                    if (
                        isset($res['value']) // 是否获取到了值
                        && isset($res['status']) // 是否有返回状态
                        && $res['status'] == 0 // 是否正常返回
                        && isset($pidArr[0]) // did
                        && isset($pidArr[1]) // sid
                        && isset($pidArr[2]) // pid
                        && isset($instances[$pidArr[0]][($pidArr[1] . '.' . $pidArr[2])]) // 是否有对应属性
                    ) {
                        $attributeName = $instances[$pidArr[0]][($pidArr[1] . '.' . $pidArr[2])]->getUrn()->getName();

                        $attributes[$pidArr[0]][$attributeName] = $res['value'];
                    }

                }
            }

            return $attributes;
        } else {
            throw new ApiErrorException('devices data required');
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

            $propertyData = [
                $did => [
                    'type' => $type,
                    'data' => $data
                ]
            ];

            return $this->setPropertiesGraceful($propertyData);
        } else {
            throw new ApiErrorException('Properties data required');
        }
    }

    /**
     * 按照名称设置多个设备属性
     *
     * @param $data
     * $data = ['AABBCD-did' => ['type' => 'urn:miot-spec-v2:device:light:0000A001:yeelink-color1:1', data => ['brightness' => 75, 'on' => true]]]
     * @return array|bool|mixed
     * @throws ApiErrorException
     * @throws \MiotApi\Exception\JsonException
     * @throws \MiotApi\Exception\SpecificationErrorException
     */
    public function setPropertiesGraceful($data)
    {
        if (!empty($data)) {
            $properties = [];
            foreach ($data as $did => $datum) {
                if (!empty($datum['data']) && isset($datum['type'])) {
                    $instance = new Instance($datum['type']);
                    $propertiesNodes = $instance->getPropertiesNodes();

                    foreach ($datum['data'] as $name => $value) {
                        list($sid, $pid) = $instance->getSidPidByName($name);

                        if (!$sid || !$pid) {
                            throw new ApiErrorException('Invalid property! did:' . $did . ',name: ' . $name);
                        }

                        $property = $propertiesNodes[($sid . '.' . $pid)];

                        if (!$property->verify($value)) {
                            throw new ApiErrorException('Invalid property value! did:' . $did . ',name: ' . $name);
                        }

                        if (!$property->canWrite()) {
                            throw new ApiErrorException('The property does\'t has the write access! did:' . $did . ',name: ' . $name);
                        }

                        $properties[] = [
                            'pid' => $did . '.' . $sid . '.' . $pid,
                            'value' => $value
                        ];
                    }
                } else {
                    throw new ApiErrorException('Properties data and device type required');
                }
            }

            return $this->setProperties([
                'properties' => $properties
            ]);
        } else {
            throw new ApiErrorException('devices data required');
        }
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