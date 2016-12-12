<?php
namespace ApigilityMessage\V1\Rest\Message;

class MessageResourceFactory
{
    public function __invoke($services)
    {
        return new MessageResource($services);
    }
}
