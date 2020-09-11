<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;

return [
    'monolog' => [
        'loggers' => [
            'base' => [
                'name' => 'default',
                'handlers' => [
                    'file' => [
                        'name' => StreamHandler::class,
                        'options' => [
                            'stream' => 'data/logs/application.log',
                            'level' => Logger::DEBUG
                        ]
                    ]
                ],
                'processors' => [
                    WebProcessor::class,
                ]
            ],
            'log' => [
                '@extends' => 'base',
                'handlers' => [
                    'file' => [
                        'options' => [
                            'stream' => 'data/logs/myotherlog.log'
                        ]
                    ]
                ]
            ]
        ],
    ]
];