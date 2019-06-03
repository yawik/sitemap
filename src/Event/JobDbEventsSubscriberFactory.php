<?php
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Sitemap\Event;

use Interop\Container\ContainerInterface;
use SlmQueue\Queue\QueuePluginManager;

/**
 * Factory for \Sitemap\Event\JobDbEventsSubscriber
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class JobDbEventsSubscriberFactory
{
    public function __invoke(
        ContainerInterface $container,
        ?string $requestedName = null,
        ?array $options = null
    ): JobDbEventsSubscriber {
        return new JobDbEventsSubscriber(
            $container->get(QueuePluginManager::class)->get('sitemap')
        );
    }
}
