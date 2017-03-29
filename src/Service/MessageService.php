<?php
/**
 * Created by PhpStorm.
 * User: figo-007
 * Date: 2016/12/11
 * Time: 13:16
 */
namespace ApigilityMessage\Service;

use Zend\ServiceManager\ServiceManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrineToolPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrinePaginatorAdapter;
use ApigilityMessage\DoctrineEntity;
use Doctrine\ORM\Query\Expr;

class MessageService
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct(ServiceManager $services)
    {
        $this->em = $services->get('Doctrine\ORM\EntityManager');
    }

    /**
     * 创建一条消息
     *
     * @param $data
     * @param $user
     * @return DoctrineEntity\Message
     */
    public function createMessage($data, $user)
    {
        $message = new DoctrineEntity\Message();
        if (isset($data->text)) $message->setText($data->text);
        $message->setCreateTime(new \DateTime());

        $message->setUser($user);

        $this->em->persist($message);
        $this->em->flush();

        return $message;
    }

    /**
     * 获取一条消息
     *
     * @param $message_id
     * @return DoctrineEntity\Message
     * @throws \Exception
     */
    public function getMessage($message_id)
    {
        $message = $this->em->find('ApigilityMessage\DoctrineEntity\Message', $message_id);
        if (empty($message)) throw new \Exception('消息不存在', 404);
        else return $message;
    }

    /**
     * 获取消息列表
     *
     * @param $params
     * @return DoctrinePaginatorAdapter
     */
    public function getMessages($params)
    {
        $qb = new QueryBuilder($this->em);
        $qb->select('m')->from('ApigilityMessage\DoctrineEntity\Message', 'm')->orderBy(new Expr\OrderBy('m.id', 'DESC'));

        $where = '';
        if (isset($params->user_id)) {
            $qb->innerJoin('m.user', 'user');
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