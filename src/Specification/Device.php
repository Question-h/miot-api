<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-8
 * Time: 下午3:43
 */
namespace Yeelight\Specification;

class Device extends Instance
{
    protected $serviceInstances;

    protected function init()
    {
        parent::init();
        $services = $this->services;
        if (!empty($services)) {
            foreach ($services as $index => $service) {
                $this->serviceInstances[] = new Service($service['type']);
            }
        }
    }
    /**
     * @return mixed
     */
    public function getServiceInstances()
    {
        return $this->serviceInstances;
    }

    /**
     * @param mixed $serviceInstances
     */
    public function setServiceInstances(Service $serviceInstances)
    {
        $this->serviceInstances = $serviceInstances;
    }
}