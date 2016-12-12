<?php
namespace ApigilityMessage\V1\Rest\Send;

class SendResourceFactory
{
    public function __invoke($services)
    {
        return new SendResource($services);
    }
}
