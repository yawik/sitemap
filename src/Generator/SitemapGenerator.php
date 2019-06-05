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

use Sitemap\Entity\LinkCollection;
use Sitemap\Options\SitemapOptions;
use Zend\Router\RouteInterface;
use samdark\sitemap\Index;
use samdark\sitemap\Sitemap;
use Sitemap\Entity\UrlLink;
use Sitemap\Entity\RouteLink;
use Zend\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class SitemapGenerator
{
    /** */
    private $options;
    private $router;
    /** @var LoggerInterface */
    private $logger;

    public function __construct(SitemapOptions $options, RouteInterface $router)
    {
        $this->options = $options;
        $this->router = $router;
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

    public function generate($name, LinkCollection $links)
    {
        $this->options->setName($name);
        $sitemap = new Sitemap($this->options->getSitemapName(), true);
        $logger = $this->getLogger();

        foreach ($links as $link) {
            $urls = [];
            $languages = $link->getLanguages() ?? $this->options->getLanguages();

            $logger->debug(sprintf(
                'Process %s: %s',
                $link instanceof RouteLink ? 'RouteLink' : 'UrlLink',
                $link instanceof RouteLink ? $link->getName() : $link->getUrl()
            ));

            foreach ($languages as $lang) {
                /* NOTE:
                 * Different url generating could be implemented using
                 * the strategy pattern.
                 * Given, that there are only two link types at the
                 * moment, it is not done yet.
                 * When adding more types, the strategy pattern should
                 * be used then, though.
                 */
                switch (true) {
                    default:
                        continue 2;

                    case $link instanceof UrlLink:
                        $logger->debug('Process url link: ' . $lang . ' => ' . $link->getUrl());
                        $url = $link->getUrl();
                        $url = str_replace('%lang%', $lang, $url);
                        break;

                    case $link instanceof RouteLink:
                        $logger->debug('Process route link:' . $lang . '=> ' . $link->getName());
                        $url = $this->router->assemble(
                            array_merge($link->getParams(), ['lang' => $lang]),
                            array_merge($link->getOptions(), ['name' => $link->getName()])
                        );
                        break;
                }

                $urls[$lang] = $this->options->prependBaseUrl($url);
            }

            $logger->debug('Urls generated: ' . PHP_EOL . ' - ' . join(PHP_EOL . ' - ', $urls));
            if (count($urls) == 1) {
                $urls = $urls[0];
                $logger->debug('Use single url: ' . $urls);
            }

            $sitemap->addItem($urls, $link->getLastModified(), $link->getChangeFrequency(), $link->getPriority());
        }

        $sitemap->write();

        $index = new Index($this->options->getSitemapIndexName());

        foreach ($sitemap->getSitemapUrls($this->options->getSitemapBaseUrl()) as $sitemapUrl) {
            $index->addSitemap($sitemapUrl);
        }

        $index->write();

        return $this->options->getSitemapUrl();
    }
}
