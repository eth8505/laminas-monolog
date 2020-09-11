<?php

use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;

return [
    'monolog' => [
        'loggers' => [
            'log' => [
                'name' => 'default',
                'handlers' => [
                    'file' => [
                        'name' => StreamHandler::class,
                        'options' => [
                            'stream' => 'data/logs/application.log',
                            'level' => Logger::DEBUG
                        ],
                        'formatter' => [
                            'name' => LogstashFormatter::class,
                            'options' => [
                                'applicationName' => 'my-application',
                                'systemName' => gethostname()
                            ]
                        ]
                    ]
                ],
                'processors' => [
                    WebProcessor::class,
                ]
            ]
        ],
    ]
];