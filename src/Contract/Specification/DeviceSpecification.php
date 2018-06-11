<?php
/**
 * Created by PhpStorm.
 * User: sheldon
 * Date: 18-6-8
 * Time: 下午3:43
 */
namespace MiotApi\Contract\Specification;

class DeviceSpecification extends Specification
{
    protected $serviceSpecifications;

    protected function init()
    {
        parent::init();
        $services = $this->services;
        if (!empty($services)) {
            foreach ($services as $index => $service) {
                $this->serviceSpecifications[] = new ServiceSpecification($service['type']);
            }
        }
    }
    /**
     * @return mixed
     */
    public function getServiceSpecifications()
    {
        return $this->serviceSpecifications;
    }

    /**
     * @param ServiceSpecification $serviceSpecifications
     */
    public function setServiceSpecifications(ServiceSpecification $serviceSpecifications)
    {
        $this->serviceSpecifications = $serviceSpecifications;
    }
}