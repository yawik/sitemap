<?php
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Sitemap\Options;

use Interop\Container\ContainerInterface;

/**
 * Factory for \Sitemap\Options\SitemapOptions
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class SitemapOptionsFactory
{
    public function __invoke(
        ContainerInterface $container,
        ?string $requestedName = null,
        ?array $options = null
    ): SitemapOptions {
        $sitemapOptions = $container->get('Config')['options'][SitemapOptions::class] ?? [];
        $languages = $container->get('Core/Options')->getSupportedLanguages();
        $sitemapOptions['languages'] = array_keys($languages);

        return new SitemapOptions($sitemapOptions);
    }
}
