<?php
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Sitemap\Queue;

use Interop\Container\ContainerInterface;
use Sitemap\Generator\SitemapGenerator;

/**
 * Factory for \Sitemap\Queue\GenerateSitemapJob
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class GenerateSitemapJobFactory
{
    public function __invoke(
        ContainerInterface $container,
        ?string $requestedName = null,
        ?array $options = null
    ): GenerateSitemapJob {
        return new GenerateSitemapJob(
            $container->get('Sitemap/Events'),
            $container->get(SitemapGenerator::class)
        );
    }
}
