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

use Zend\Stdlib\AbstractOptions;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class SitemapOptions extends AbstractOptions
{
    protected $name;
    protected $baseUrl;
    /** @var array */
    protected $languages = [];

    /**
     * Get name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param mixed $name
     */
    public function withName($name): self
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    public function getSitemapName(): string
    {
        return './public/static/sitemaps/' . $this->name . '.xml';
    }

    /**
     * Get baseUrl
     *
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function prependBaseUrl(string $url): string
    {
        return $this->baseUrl . ltrim($url, '/');
    }

    /**
     * Set baseUrl
     *
     * @param mixed $baseUrl
     */
    public function setBaseUrl($baseUrl): void
    {
        $this->baseUrl = rtrim($baseUrl, '/') . '/';
    }

    public function getSitemapBaseUrl(): string
    {
        return $this->baseUrl . 'static/sitemaps/';
    }

    public function getSitemapIndexName(): string
    {
        return './public/static/sitemaps/' . $this->name . '_index.xml';
    }

    public function getSitemapUrl(): string
    {
        return $this->baseUrl . 'static/sitemaps/' . $this->name . '_index.xml';
    }

    /**
     * Get languages
     *
     * @return array
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * Set languages
     *
     * @param array $languages
     */
    public function setLanguages(array $languages): void
    {
        $this->languages = $languages;
    }
}
