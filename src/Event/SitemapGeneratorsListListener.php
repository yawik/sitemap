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

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class SitemapGeneratorsListListener
{
    public function __invoke(ListSitemapGeneratorsEvent $event)
    {
        $event->add(
            'jobs',
            'generate a sitemap for all active jobs.'
        );
    }
}
