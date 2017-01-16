<?php
/**
 * Created by PhpStorm.
 * User: figo-007
 * Date: 2016/12/11
 * Time: 12:30
 */
namespace ApigilityMessage\DoctrineEntity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;
use ApigilityUser\DoctrineEntity\User;

/**
 * Class Send
 * @package ApigilityMessage\DoctrineEntity
 * @Entity @Table(name="apigilitymessage_send")
 */
class Send
{
    const STATUS_UNREAD = 1;
    const STATUS_READ = 2;

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
     * 阅读状态
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
        return $this->message;
    }

    public function setSendTime(\DateTime $send_time)
    {
        $this->send_time = $send_time;
        return $this;
    }

    public function getSendTime()
    {
        return $this->send_time;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
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