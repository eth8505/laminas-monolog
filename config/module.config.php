<?php

use LaminasMonolog\Factory\Factory\LoggerFactoryFactory;
use LaminasMonolog\Factory\LoggerAbstractFactory;
use LaminasMonolog\Factory\LoggerFactory;
use LaminasMonolog\Factory\ReflectionAbstractFactory;
use LaminasMonolog\Formatter\Factory\FormatterPluginManagerFactory;
use LaminasMonolog\Formatter\FormatterPluginManager;
use LaminasMonolog\Handler\Factory\HandlerPluginManagerFactory;
use LaminasMonolog\Handler\HandlerPluginManager;
use LaminasMonolog\Processor\Factory\ProcessorPluginManagerFactory;
use LaminasMonolog\Processor\ProcessorPluginManager;

return [

    'service_manager' => [
        'invokables' => [
            ReflectionAbstractFactory::class
        ],
        'factories' => [
            LoggerFactory::class => LoggerFactoryFactory::class,
            HandlerPluginManager::class => HandlerPluginManagerFactory::class,
            FormatterPluginManager::class => FormatterPluginManagerFactory::class,
            ProcessorPluginManager::class => ProcessorPluginManagerFactory::class
        ],
        'abstract_factories' => [
            LoggerAbstractFactory::class
        ]
    ],

    'monolog' => [
        'formatters' => [
            'abstract_factories' => [
                ReflectionAbstractFactory::class
            ]
        ],
        'handlers' => [
            'abstract_factories' => [
                ReflectionAbstractFactory::class
            ]
        ],
        'processors' => [
            'abstract_factories' => [
                ReflectionAbstractFactory::class
            ]
        ]
    ]
];
