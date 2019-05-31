<?php
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Sitemap\Controller\Plugin;

use Interop\Container\ContainerInterface;

/**
 * Factory for \Sitemap\Controller\Plugin\Sitemap
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class SitemapFactory
{
    public function __invoke(
        ContainerInterface $container,
        ?string $requestedName = null,
        ?array $options = null
    ): Sitemap {
        return new Sitemap(
            $container->get('ControllerPluginManager')->get('queue')
        );
    }
}
