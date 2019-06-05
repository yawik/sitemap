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

use Zend\EventManager\Event;
use Sitemap\Entity\LinkCollection;
use Zend\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class GenerateSitemapEvent extends Event
{
    const FETCH_LINKS = "sitemap.fetch-links";

    private $sitemapName;
    private $linkCollection;
    private $logger;

    public static function getEventName(string $name): string
    {
        return "sitemap.fetch-$name";
    }

    public function setSitemapName(string $name): void
    {
        $this->sitemapName = $name;
    }

    public function getSitemapname(): ?string
    {
        return $this->sitemapName;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function getLogger(): LoggerInterface
    {
        if (!$this->logger) {
            $this->logger = new class implements LoggerInterface
            {
                use LoggerTrait;
                //phpcs:ignore
                public function log($level, $message, array $context = []) {}
            };
        }

        return $this->logger;
    }

    public function getLinkCollection(): LinkCollection
    {
        if (!$this->linkCollection) {
            $this->linkCollection = new LinkCollection();
        }

        return $this->linkCollection;
    }

    public function setParams($params)
    {
        foreach ($params as $key => $value) {
            $setter = 'set' . str_replace('_', '', $key);

            if (method_exists($this, $setter)) {
                $this->$setter($value);
                unset($params[$key]);
            }
        }

        return parent::setParams($params);
    }
}
