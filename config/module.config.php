<?php
return [
    'service_manager' => [
        'factories' => [
            \ApigilityMessage\V1\Rest\Message\MessageResource::class => \ApigilityMessage\V1\Rest\Message\MessageResourceFactory::class,
            \ApigilityMessage\V1\Rest\Send\SendResource::class => \ApigilityMessage\V1\Rest\Send\SendResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'apigility-message.rest.message' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/message/message[/:message_id]',
                    'defaults' => [
                        'controller' => 'ApigilityMessage\\V1\\Rest\\Message\\Controller',
                    ],
                ],
            ],
            'apigility-message.rest.send' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/message/send[/:send_id]',
                    'defaults' => [
                        'controller' => 'ApigilityMessage\\V1\\Rest\\Send\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'apigility-message.rest.message',
            1 => 'apigility-message.rest.send',
        ],
    ],
    'zf-rest' => [
        'ApigilityMessage\\V1\\Rest\\Message\\Controller' => [
            'listener' => \ApigilityMessage\V1\Rest\Message\MessageResource::class,
            'route_name' => 'apigility-message.rest.message',
            'route_identifier_name' => 'message_id',
            'collection_name' => 'message',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'user_id',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \ApigilityMessage\V1\Rest\Message\MessageEntity::class,
            'collection_class' => \ApigilityMessage\V1\Rest\Message\MessageCollection::class,
            'service_name' => 'Message',
        ],
        'ApigilityMessage\\V1\\Rest\\Send\\Controller' => [
            'listener' => \ApigilityMessage\V1\Rest\Send\SendResource::class,
            'route_name' => 'apigility-message.rest.send',
            'route_identifier_name' => 'send_id',
            'collection_name' => 'send',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'user_id',
                1 => 'message_id',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \ApigilityMessage\V1\Rest\Send\SendEntity::class,
            'collection_class' => \ApigilityMessage\V1\Rest\Send\SendCollection::class,
            'service_name' => 'Send',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'ApigilityMessage\\V1\\Rest\\Message\\Controller' => 'HalJson',
            'ApigilityMessage\\V1\\Rest\\Send\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'ApigilityMessage\\V1\\Rest\\Message\\Controller' => [
                0 => 'application/vnd.apigility-message.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'ApigilityMessage\\V1\\Rest\\Send\\Controller' => [
                0 => 'application/vnd.apigility-message.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'ApigilityMessage\\V1\\Rest\\Message\\Controller' => [
                0 => 'application/vnd.apigility-message.v1+json',
                1 => 'application/json',
            ],
            'ApigilityMessage\\V1\\Rest\\Send\\Controller' => [
                0 => 'application/vnd.apigility-message.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \ApigilityMessage\V1\Rest\Message\MessageEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'apigility-message.rest.message',
                'route_identifier_name' => 'message_id',
                'hydrator' => \Zend\Hydrator\ClassMethods::class,
            ],
            \ApigilityMessage\V1\Rest\Message\MessageCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'apigility-message.rest.message',
                'route_identifier_name' => 'message_id',
                'is_collection' => true,
            ],
            \ApigilityMessage\V1\Rest\Send\SendEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'apigility-message.rest.send',
                'route_identifier_name' => 'send_id',
                'hydrator' => \Zend\Hydrator\ClassMethods::class,
            ],
            \ApigilityMessage\V1\Rest\Send\SendCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'apigility-message.rest.send',
                'route_identifier_name' => 'send_id',
                'is_collection' => true,
            ],
        ],
    ],
    'zf-mvc-auth' => [
        'authorization' => [
            'ApigilityMessage\\V1\\Rest\\Message\\Controller' => [
                'collection' => [
                    'GET' => false,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
            ],
        ],
    ],
];
