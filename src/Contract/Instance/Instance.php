<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-6
 * Time: 下午6:25
 */
namespace MiotApi\Contract\Instance;

use MiotApi\Contract\RemoteSpec;
use MiotApi\Contract\Specification\DeviceSpecification;
use MiotApi\Contract\Specification\Specification;
use MiotApi\Util\Collection\Collection;

class Instance extends Specification
{
    protected $servicesNode;

    /**
     * type对象
     * @var
     */
    protected $specification;

    /**
     * @throws \MiotApi\Exception\SpecificationErrorException
     */
    public function init()
    {
        $items = RemoteSpec::instance($this->urn->getExpression());
        $this->collection = new Collection($items);
        $this->specification = new DeviceSpecification($this->getType());
        $this->initServices();
    }

    /**
     * @throws \MiotApi\Exception\SpecificationErrorException
     */
    public function initServices()
    {
        if ($this->has('services')) {
            $services = $this->get('services');
            if (!empty($services)) {
                foreach ($services as $index => $service) {
                    $this->servicesNode[$service['iid']] = new Service($service);
                }
            }
        }
    }

    /**
     * 根据服务的实例id获取服务实例
     *
     * @param $siid
     * @return mixed
     */
    public function service($siid)
    {
        return $this->servicesNode[$siid];
    }

    /**
     * 根据服务的实例id和属性的实例id获取属性实例
     *
     * @param $siid
     * @param $piid
     * @return mixed
     */
    public function property($siid, $piid)
    {
        return $this->getPropertiesNode($siid)[$piid];
    }

    /**
     * 获取设备的服务实例列表
     *
     * @return mixed
     */
    public function getServicesNode()
    {
        return $this->servicesNode;
    }

    /**
     * 根据服务实例id 获取属性列表
     * @param $siid
     * @return mixed
     */
    public function getPropertiesNode($siid)
    {
        $service = $this->service($siid);

        return $service->getPropertiesNode;
    }

    public function getSpecification()
    {
        return $this->specification;
    }

    public function getSpecificationContext()
    {
        return $this->specification->toContext();
    }
}