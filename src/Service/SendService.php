<?php
/**
 * Created by PhpStorm.
 * User: figo-007
 * Date: 2016/12/11
 * Time: 13:17
 */
namespace ApigilityMessage\Service;

use Zend\ServiceManager\ServiceManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrineToolPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrinePaginatorAdapter;
use ApigilityMessage\DoctrineEntity;

class SendService
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \ApigilityMessage\Service\MessageService
     */
    protected $messageService;

    /**
     * @var \ApigilityUser\Service\UserService
     */
    protected $userService;

    public function __construct(ServiceManager $services)
    {
        $this->em = $services->get('Doctrine\ORM\EntityManager');
        $this->messageService = $services->get('ApigilityMessage\Service\MessageService');
        $this->userService = $services->get('ApigilityUser\Service\UserService');
    }

    /**
     * 创建一个发送
     *
     * @param $data
     * @return DoctrineEntity\Send
     */
    public function createSend($data)
    {
        $message = $this->messageService->getMessage($data->message_id);
        $user = $this->userService->getUser($data->user_id);

        $send = new DoctrineEntity\Send();
        $send->setMessage($message)
            ->setUser($user);
        $send->setSendTime(new \DateTime());
        $send->setStatus(DoctrineEntity\Send::STATUS_UNREAD);

        $this->em->persist($send);
        $this->em->flush();

        return $send;
    }

    /**
     * 获取一个发送
     *
     * @param $send_id
     * @return DoctrineEntity\Send
     * @throws \Exception
     */
    public function getSend($send_id)
    {
        $send = $this->em->find('ApigilityMessage\DoctrineEntity\Send', $send_id);
        if (empty($send)) throw new \Exception('找不到发送数据', 404);
        else return $send;
    }

    /**
     * 获取发送列表
     *
     * @param $params
     * @return DoctrinePaginatorAdapter
     */
    public function getSends($params)
    {
        $qb = new QueryBuilder($this->em);
        $qb->select('s')->from('ApigilityMessage\DoctrineEntity\Send', 's');

        $where = '';
        if (isset($params->user_id)) {
            $qb->innerJoin('s.user', 'user');
            if (!empty($where)) $where .= ' AND ';
            $where .= 'user.id = :user_id';
        }

        if (!empty($where)) {
            $qb->where($where);
            if (isset($params->user_id)) $qb->setParameter('user_id', $params->user_id);
        }

        $doctrine_paginator = new DoctrineToolPaginator($qb->getQuery());
        return new DoctrinePaginatorAdapter($doctrine_paginator);
    }
}