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

/**
 * Factory for \Sitemap\Event\FetchJobLinksListener
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class FetchJobLinksListenerFactory
{
    public function __invoke(
        ContainerInterface $container,
        ?string $requestedName = null,
        ?array $options = null
    ): FetchJobLinksListener {
        return new FetchJobLinksListener(
            $container->get('repositories')->get('Jobs')
        );
    }
}
