<?php
namespace ApigilityMessage\V1\Rest\Send;

use ApigilityCatworkFoundation\Base\ApigilityObjectStorageAwareCollection;

class SendCollection extends ApigilityObjectStorageAwareCollection
{
    protected $itemType = SendEntity::class;
}
