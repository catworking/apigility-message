<?php
namespace ApigilityMessage\V1\Rest\Send;

use ApigilityCatworkFoundation\Base\ApigilityObjectStorageAwareEntity;
use ApigilityMessage\DoctrineEntity\Message;
use ApigilityMessage\V1\Rest\Message\MessageEntity;
use ApigilityUser\DoctrineEntity\User;
use ApigilityUser\V1\Rest\User\UserEntity;

class SendEntity extends ApigilityObjectStorageAwareEntity
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * 要发送的消息
     *
     * @ManyToOne(targetEntity="Message", inversedBy="sends")
     * @JoinColumn(name="message_id", referencedColumnName="id")
     */
    protected $message;

    /**
     * 发送时间
     *
     * @Column(type="datetime", nullable=false)
     */
    protected $send_time;

    /**
     * 发送目标，ApigilityUser组件的User对象
     *
     * @ManyToOne(targetEntity="ApigilityUser\DoctrineEntity\User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * 订单状态
     *
     * @Column(type="smallint", nullable=false)
     */
    protected $status;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage()
    {
        if ($this->message instanceof Message) return $this->hydrator->extract(new MessageEntity($this->message, $this->serviceManager));
        else return $this->message;
    }

    public function setSendTime(\DateTime $send_time)
    {
        $this->send_time = $send_time;
        return $this;
    }

    public function getSendTime()
    {
        if ($this->send_time instanceof \DateTime) return $this->send_time->getTimestamp();
        else return $this->send_time;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        if ($this->user instanceof User) return $this->hydrator->extract(new UserEntity($this->user, $this->serviceManager));
        else return $this->user;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
