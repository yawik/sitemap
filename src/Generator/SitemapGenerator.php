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
use Laminas\Router\RouteInterface;
use samdark\sitemap\Index;
use samdark\sitemap\Sitemap;
use Sitemap\Entity\UrlLink;
use Sitemap\Entity\RouteLink;
use Laminas\Log\LoggerInterface;
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

    public function getOptions(): SitemapOptions
    {
        return clone $this->options;
    }

    public function generate($name, LinkCollection $links)
    {
        $options = $this->options->withName($name);
        $sitemap = new Sitemap($options->getSitemapName(), true);
        $logger = $this->getLogger();

        foreach ($links as $link) {
            $urls = [];
            $languages = ['de']; //$link->getLanguages() ?? $options->getLanguages();

            $logger->debug(sprintf(
                'Process %s(%s) for languages: %s',
                $link instanceof RouteLink ? 'RouteLink' : 'UrlLink',
                $link instanceof RouteLink ? $link->getName() : $link->getUrl(),
                join(', ', $languages)
            ));

            if (empty($languages)) {
                $languages = ['_disabled_'];
            }

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
                        $url = $link->getUrl();
                        if ($lang != '_disabled_') {
                            $url = str_replace('%lang%', $lang, $url);
                        }
                        break;

                    case $link instanceof RouteLink:
                        $routeParams = $link->getParams();

                        if ($lang != '_disabled_') {
                            $routeParams = array_merge($routeParams, ['lang' => $lang]);
                        }
                        $url = $this->router->assemble(
                            $routeParams,
                            array_merge($link->getOptions(), ['name' => $link->getName()])
                        );
                        break;
                }

                $urls[$lang] = $this->options->prependBaseUrl($url);
            }

            if (count($urls) == 1) {
                $urls = array_pop($urls);
                $logger->debug('Use single url: ' . $urls);
            } else {
                $logger->debug('Urls generated: ' . PHP_EOL . var_export($urls, true));
            }

            $sitemap->addItem($urls, $link->getLastModified(), $link->getChangeFrequency(), $link->getPriority());
        }

        $sitemap->write();

        $index = new Index($options->getSitemapIndexName());

        foreach ($sitemap->getSitemapUrls($options->getSitemapBaseUrl()) as $sitemapUrl) {
            $index->addSitemap($sitemapUrl);
        }

        $index->write();

        return $options->getSitemapUrl();
    }
}
