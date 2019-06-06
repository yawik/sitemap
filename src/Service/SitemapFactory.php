<?php declare(strict_types=1);
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Sitemap\Service;

use Interop\Container\ContainerInterface;
use Sitemap\Generator\SitemapGenerator;

/**
 * Factory for \Sitemap\Service\Sitemap
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
            $container->get('Sitemap/Events'),
            $container->get(SitemapGenerator::class)
        );
    }
}
