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

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class RouteLink extends AbstractLink
{
    private $name;
    private $params = [];

    public function __construct(?string $name = null, ?array $params = [])
    {
        if ($name !== null) {
            $this->setName($name);
            $this->setParams($params);
        }
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        if ($this->name === null) {
            throw new \UnexpectedValueException('Name must be set.');
        }

        return $this->name;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
