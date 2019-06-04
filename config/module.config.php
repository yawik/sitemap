<?php

declare(strict_types=1);

namespace Sitemap;

return [
    'service_manager' => [
        'factories' => [
            Generator\SitemapGenerator::class => Generator\SitemapGeneratorFactory::class,
            Options\SitemapOptions::class => Options\SitemapOptionsFactory::class,
            Event\FetchJobLinksListener::class => Event\FetchJobLinksListenerFactory::class,
            Event\JobDbEventsSubscriber::class => Event\JobDbEventsSubscriberFactory::class,
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\ConsoleController::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
        ],
    ],

    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\Sitemap::class => Controller\Plugin\SitemapFactory::class,
            Controller\Plugin\ListSitemapGenerators::class => Controller\Plugin\ListSitemapGeneratorsFactory::class,
        ],
        'aliases' => [
            'sitemap' => Controller\Plugin\Sitemap::class,
        ]
    ],

    'doctrine' => [
        'eventmanager' => [
            'odm_default' => [
                'subscribers' => [
                    Event\JobDbEventsSubscriber::class,
                ],
            ],
        ],
    ],

    'event_manager' => [
        'Sitemap/Events' => [
            'service' => 'Core/EventManager',
            'event' => Event\GenerateSitemapEvent::class,
            'listeners' => [
                Event\FetchJobLinksListener::class => [Event\GenerateSitemapEvent::getEventName('jobs'), true],
            ],
        ],
        'Sitemap/ListGenerators/Events' => [
            'service' => 'Core/EventManager',
            'event' => Event\ListSitemapGeneratorsEvent::class,
            'listeners' => [
                Event\SitemapGeneratorsListListener::class => Event\ListSitemapGeneratorsEvent::FETCH,
            ],
        ],
    ],

    'slm_queue' => [
        'queues' => [
            'sitemap' => [
                'collection' => 'sitemap.queue',
            ],
        ],
        'worker_strategies' => [
            'queues' => [
                'sitemap' => [
                    \Core\Queue\Strategy\LogStrategy::class => ['log' => 'Log/Sitemap/Queue'],
                    \SlmQueue\Strategy\ProcessQueueStrategy::class,
                ],
            ],
        ],
        'queue_manager' => [
            'factories' => [
                'sitemap' => \Core\Queue\MongoQueueFactory::class,
            ],
        ],
        'job_manager' => [
            'factories' => [
                Queue\GenerateSitemapJob::class => Queue\GenerateSitemapJobFactory::class,
            ],
        ],
    ],
    'log' => [
        'Log/Sitemap/Queue' => [
            'writers' => [
                [
                    'name' => 'stream',
                    'priority' => 1000,
                    'options' => [
                        'stream' => getcwd() . '/var/log/sitemap.queue.log',
                        'formatter'  => [
                            'name' => 'simple',
                            'options' => [
                                'format' => '%timestamp% (%pid%) %priorityName%: %message% %extra%',
                                'dateTimeFormat' => 'd.m.Y H:i:s',
                            ],
                        ],
                    ],
                ],
            ],
            'processors' => [
                ['name' => \Core\Log\Processor\ProcessId::class],
            ],
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'sitemap-generate' => [
                    'options' => [
                        'route' => 'sitemap generate <name>',
                        'defaults' => [
                            'controller' => Controller\ConsoleController::class,
                            'action' => 'generate',
                        ]
                    ]
                ],
                'sitemap-list' => [
                    'options' => [
                        'route' => 'sitemap list',
                        'defaults' => [
                            'controller' => Controller\ConsoleController::class,
                            'action' => 'list'
                        ],
                    ],
                ],
            ],
        ],
    ],
];
