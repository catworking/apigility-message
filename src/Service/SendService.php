<?php
/**
 * Created by PhpStorm.
 * User: figo-007
 * Date: 2016/12/11
 * Time: 13:17
 */
namespace ApigilityMessage\Service;

use ApigilityUser\DoctrineEntity\User;
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
     * @throws \Exception
     */
    public function createSend($data)
    {
        ignore_user_abort(true);
        set_time_limit(0);
        ini_set('memory_limit', -1);

        $message = $this->messageService->getMessage($data->message_id);

        $em = $this->em;
        $create_send = function (User $user, $flush = true) use ($em, $message, $data) {
            // 先检查用户有没有发送过此消息
            $sends = $this->getSends((object)[
                'user_id'=>$user->getId(),
                'message_id'=>$message->getId()
            ]);

            if ($sends->count()) return false;

            $send = new DoctrineEntity\Send();
            $send->setMessage($message)
                ->setUser($user);
            $send->setSendTime(new \DateTime());
            $send->setStatus(DoctrineEntity\Send::STATUS_UNREAD);

            $em->persist($send);
            if ($flush) $em->flush();

            return $send;
        };

        if (isset($data->user_filters)) {
            // 批量发送
            $user_filters = $data->user_filters;
            $users = $this->em->getRepository('ApigilityUser\DoctrineEntity\User')->findBy($user_filters);

            if (count($users)) {
                $sends = array();
                foreach ($users as $user) {
                    $send = $create_send($user, false);
                    if ($send) $sends[] = $send;
                }

                if (count($sends)) {
                    $this->em->flush();

                    header('sent-total:'.count($sends));

                    return end($sends);
                }
            }

            throw new \Exception('没有匹配的投递目标');
        } else {
            // 单用户发送
            $user = $this->userService->getUser($data->user_id);

            $send = $create_send($user);
            if (empty($send)) throw new \Exception('已经向此用户发送过该消息');

            return $send;
        }
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

        if (isset($params->message_id)) {
            $qb->innerJoin('s.message', 'm');
            if (!empty($where)) $where .= ' AND ';
            $where .= 'm.id = :message_id';
        }

        if (!empty($where)) {
            $qb->where($where);
            if (isset($params->user_id)) $qb->setParameter('user_id', $params->user_id);
            if (isset($params->message_id)) $qb->setParameter('message_id', $params->message_id);
        }

        $doctrine_paginator = new DoctrineToolPaginator($qb->getQuery());
        return new DoctrinePaginatorAdapter($doctrine_paginator);
    }
}