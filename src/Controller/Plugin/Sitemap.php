<?php declare(strict_types=1);
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Sitemap\Controller\Plugin;

use Sitemap\Service\Sitemap as SitemapService;
use Zend\Log\LoggerInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class Sitemap extends AbstractPlugin
{
    /** @var SitemapService */
    private $sitemap;
    private $logger;

    public function __construct(SitemapService $sitemap, LoggerInterface $logger)
    {
        $this->sitemap = $sitemap;
        $this->logger = $logger;
    }

    public function __invoke(string $name, bool $ping = false): void
    {
        $this->sitemap->setLogger($this->logger);
        $this->sitemap->{$ping ? 'ping' : 'generate'}($name);
    }
}
