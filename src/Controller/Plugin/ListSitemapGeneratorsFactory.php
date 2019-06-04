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
 * Factory for \Sitemap\Controller\Plugin\ListSitemapGenerators
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class ListSitemapGeneratorsFactory
{
    public function __invoke(
        ContainerInterface $container,
        ?string $requestedName = null,
        ?array $options = null
    ): ListSitemapGenerators {
        return new ListSitemapGenerators(
            $container->get('Sitemap/ListGenerators/Events')
        );
    }
}
