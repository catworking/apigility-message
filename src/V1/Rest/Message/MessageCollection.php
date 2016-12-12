<?php
namespace ApigilityMessage\V1\Rest\Message;

use ApigilityCatworkFoundation\Base\ApigilityObjectStorageAwareCollection;

class MessageCollection extends ApigilityObjectStorageAwareCollection
{
    protected $itemType = MessageEntity::class;
}
