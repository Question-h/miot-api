<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-14
 * Time: 下午4:39
 */

namespace MiotApi\Api;

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
     * @param $name
     */
    public function getPropertyByName($did, $name)
    {

    }
}