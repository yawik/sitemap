<?php
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Sitemap\Generator;

use Interop\Container\ContainerInterface;
use Sitemap\Options\SitemapOptions;

/**
 * Factory for \Sitemap\Generator\SitemapGenerator
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class SitemapGeneratorFactory
{
    public function __invoke(
        ContainerInterface $container,
        ?string $requestedName = null,
        ?array $options = null
    ): SitemapGenerator {
        return new SitemapGenerator(
            $container->get(SitemapOptions::class),
            $container->get('HttpRouter')
        );
    }
}
