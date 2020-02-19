<?php declare(strict_types=1);
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Sitemap\Queue;

use Interop\Container\ContainerInterface;
use Sitemap\Service\Sitemap;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for \Sitemap\Queue\GenerateSitemapJob
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class SitemapJobFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        ?string $requestedName = null,
        ?array $options = null
    ): GenerateSitemapJob {
        return new $requestedName(
            $container->get(Sitemap::class)
        );
    }
}
