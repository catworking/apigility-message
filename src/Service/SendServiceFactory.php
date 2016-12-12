<?php
/**
 * Created by PhpStorm.
 * User: figo-007
 * Date: 2016/12/11
 * Time: 13:17
 */
namespace ApigilityMessage\Service;

use Zend\ServiceManager\ServiceManager;

class SendServiceFactory
{
    public function __invoke(ServiceManager $services)
    {
        return new SendService($services);
    }
}