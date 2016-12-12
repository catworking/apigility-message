<?php
/**
 * Created by PhpStorm.
 * User: figo-007
 * Date: 2016/12/11
 * Time: 13:16
 */
namespace ApigilityMessage\Service;

use Zend\ServiceManager\ServiceManager;

class MessageServiceFactory
{
    public function __invoke(ServiceManager $services)
    {
        return new MessageService($services);
    }
}