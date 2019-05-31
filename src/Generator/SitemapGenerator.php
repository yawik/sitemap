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

    public function __construct(SitemapOptions $options, RouteInterface $router)
    {
        $this->options = $options;
        $this->router = $router;
    }

    public function generate($name, LinkCollection $links)
    {
        $this->options->setName($name);
        $sitemap = new Sitemap($this->options->getSitemapName(), true);
        foreach ($links as $link) {
            $urls = [];
            foreach ($this->options->getLanguages() as $lang) {
                switch (true) {
                    default:
                        continue 2;

                    case $link instanceof UrlLink:
                        $url = $link->getUrl();
                        $url = str_replace('%lang%', $lang, $url);
                        break;

                    case $link instanceof RouteLink:
                        $url = $this->router->assemble(
                            array_merge($link->getParams(), ['lang' => $lang]),
                            ['name' => $link->getName()]
                        );
                        break;
                }

                $urls[$lang] = $this->options->getBaseUrl() . $url;
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
