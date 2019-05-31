<?php
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Sitemap\Entity;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class LinkCollection implements IteratorAggregate, Countable
{
    private $links = [];

    public function getIterator()
    {
        return new ArrayIterator($this->links);
    }

    public function count()
    {
        return count($this->links);
    }

    public function addLink(AbstractLink ...$link): void
    {
        foreach ($link as $l) {
            $this->links[] = $l;
        }
    }

    public function addUrlLink(string ...$url): void
    {
        foreach ($url as $u) {
            $this->links[] = new UrlLink($u);
        }
    }

    public function addRouteLink(string $name, array $params = []): void
    {
        $this->links[] = new RouteLink($name, $params);
    }

    public function addRouteLinks(...$links): void
    {
        foreach ($links as $l) {
            $this->links = new RouteLink($l[0], $l[1] ?? []);
        }
    }
}
