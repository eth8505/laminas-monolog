# LaminasMonolog - ZF3 module integrating monolog with zend framework v3
The **LaminasMonolog** module integrates [monolog/monolog](https://github.com/seldaek/monolog) as a zf3-module 
via [laminas/laminas-servicemanager](https://github.com/laminas/laminas-servicemanager).

[![Build Status](https://travis-ci.org/eth8505/laminas-monolog.svg?branch=master)](https://travis-ci.org/eth8505/laminas-monolog)
![Packagist](https://img.shields.io/packagist/dt/eth8505/laminas-monolog.svg)
![Packagist Version](https://img.shields.io/packagist/v/eth8505/laminas-monolog.svg)
![PHP from Packagist](https://img.shields.io/packagist/php-v/eth8505/laminas-monolog.svg)

## How to install

:warning: Please note that this package requires at least php 7.3.  
Install `eth8505/laminas-monolog` package via composer.

~~~bash
$ composer require eth8505/laminas-monolog
~~~

Load the module in your `application.config.php` file like so:

~~~php
<?php

return [
	'modules' => [
		'LaminasMonolog',
		// ...
	],
];
~~~

## How to use
In your application config (usually located in `config/autoload/monolog.global.php`), specify your monolog in the 
`monolog/loggers` key.

### Configuring loggers
Each key (```Log\MyApp``` in the sample code) can contain a separate logger config and is available directly via the
service manager. 

~~~php
return [
    'monolog' => [
        'loggers' => [
            'Log\MyApp' => [
                'name' => 'default'
            ]
        ]
    ]
];
~~~

Each logger config is available direcly via the service manager.
~~~php
$logger = $container->get('Log\MyApp');
~~~

### Adding log handlers
Multiple [handlers](https://github.com/Seldaek/monolog/blob/master/doc/02-handlers-formatters-processors.md#handlers) 
can be added to a logger config via the ```handlers``` key.
~~~php
return [
    'monolog' => [
        'loggers' => [
            'Log\MyApp' => [
                'name' => 'default',
                'handlers' => [
                    'stream' => [
                        'name' => StreamHandler::class,
                        'options' => [
                            'path'   => 'data/log/myapp.log',
                            'level'  => Logger::DEBUG
                        ],
                    ],
                    'fire_php' => [
                        'name' => ChromePHPHandler:class
                    ]
                ]
            ]
        ]
    ]
];
~~~

### Using formatters
Each handler can be configured with a [formatter](https://github.com/Seldaek/monolog/blob/master/doc/02-handlers-formatters-processors.md#formatters) 
in order to specify a specific format. This can be useful whenlogging to [logstash](https://www.elastic.co/de/products/logstash) 
for example.

~~~php
return [
    'monolog' => [
        'loggers' => [
            'Log\MyApp' => [
                'name' => 'default',
                'handlers' => [
                    'stream' => [
                        'name' => StreamHandler::class,
                        'options' => [
                            'path'   => 'data/log/myapp.log',
                            'level'  => Logger::DEBUG
                        ],
                        'formatter' => [
                            'name' => LogstashFormatter::class,
                            'options' => [
                                'applicationName' => 'myApp',
                                'systemName' => gethostname()
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
~~~

### Using processors
[Processors](https://github.com/Seldaek/monolog/blob/master/doc/02-handlers-formatters-processors.md#processors) can be
used to enrich the logged data with additional data. The [WebProcessor](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Processor/WebProcessor.php)
can for example be used to add the request URI and client IP to the log record.
~~~php
return [
    'monolog' => [
        'loggers' => [
            'Log\MyApp' => [
                'name' => 'default'
                'processors' => [
                    WebProcessor::class
                ]
            ]
        ]
    ]
];
~~~

### Special syntax
When configuring handlers, formatters or processors, you can either specify a class name in string (or ::class constant)
format
~~~php
return [
    'monolog' => [
        'loggers' => [
            'Log\MyApp' => [
                'name' => 'default'
                'processors' => [
                    WebProcessor::class
                ]
            ]
        ]
    ]
];
~~~

or alternatively in name/options array notation, where the options are translated into the respective classes
constructor parameters by using [Reflection](https://php.net/Reflection) based 
[Named parameters](https://en.wikipedia.org/wiki/Named_parameter). 
~~~php
return [
    'monolog' => [
        'loggers' => [
            'Log\MyApp' => [
                'name' => 'default'
                'processors' => [
                    [
                        'name' => WebProcessor::class,
                        'options' => [
                            'extraFields' => [
                                'url' => 'REQUEST_URI',
                                'http_method' => 'REQUEST_METHOD',
                                'server' => 'SERVER_NAME'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
~~~

### Custom handlers, processors and formatters
Since this module creates everything via the service manager using 
[plugin managers](https://docs.zendframework.com/zend-servicemanager/plugin-managers/), custom handlers, 
processors and formatters can be easily registered, by adding them to the respective config keys

~~~php
return [
    'monolog' => [
        'formatters' => [
            factories' => [
                MyCustomFormatter::class => MyCustomFormatterFactory::class
            ]
        ],
        'handlers' => [
            'factories' => [
                MyCustomHandler::class => MyCustomHandlerFactory::class
            ]
        ],
        'processors' => [
            'factories' => [
                MyCustomProcessor::class => MyCustomProcessorFactory::class
            ]
        ]
    ]
];
~~~
:warning: Note that only formatters using custom factories need to be explicitly registered. Any other handler
configured will be automatically created using the internal, reflection-based factories.

### Extending log handlers
You can define default loggers and inherit from them in other loggers.
~~~php
return [
    'monolog' => [
        'loggers' => [
            'base' => [
                // default logger config
            ],
            'inherited' => [
                '@extends' => 'base'
            ]
        ]
    ]
];
~~~
:information_source: Even though recursion is supported here as of Version 1.0.3, it is limited to 10 levels and will
throw a ```LaminasMonolog\Exception\RuntimeException``` if recursed any deeper. 

See [example config](samples/extend-config.example.config.php) for details.

## Thanks
Thanks to [neckeloo](https://github.com/neeckeloo) and his [Monolog Module](https://github.com/neeckeloo/monolog-module)
and [enlitepro](https://github.com/enlitepro) for their [Enlite Monolog](https://github.com/enlitepro/enlite-monolog)
as they served as a template for this module.
