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

use Laminas\EventManager\Event;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class ListSitemapGeneratorsEvent extends Event
{
    const FETCH = 'sitempap.fetch-generators';

    private $list = [];

    public function add(string $name, string $info = '')
    {
        $this->list[$name] = $info;
    }

    public function getList()
    {
        $list = $this->list;
        ksort($list);

        return $list;
    }
}
