<?php declare(strict_types=1);
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Sitemap\Event;

use Core\Queue\MongoQueue;
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
            function () use ($container): MongoQueue {
                return $container->get(QueuePluginManager::class)->get('sitemap');
            }
        );
    }
}
