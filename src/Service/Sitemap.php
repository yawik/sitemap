<?php declare(strict_types=1);
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Sitemap\Service;

use Core\EventManager\EventManager;
use Sitemap\Event\GenerateSitemapEvent;
use Sitemap\Generator\SitemapGenerator;
use Laminas\Http\Client;
use Laminas\Log\LoggerAwareInterface;
use Laminas\Log\LoggerAwareTrait;
use function urldecode;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class Sitemap implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $events;
    private $generator;

    public function __construct(EventManager $events, SitemapGenerator $generator)
    {
        $this->events = $events;
        $this->generator = $generator;
    }

    public function generate(string $name): SitemapResult
    {
        $logger = $this->getLogger();

        $event = $this->events->getEvent(
            GenerateSitemapEvent::getEventName($name),
            $this,
            [
                'sitemap_name' => $name,
                'logger' => $logger,
            ]
        );
        $this->events->triggerEvent($event);
        $linkCollection = $event->getLinkCollection();

        if (!count($linkCollection)) {
            $logger->notice('No links were fetched.');
            return new SitemapResult(false, 'No links were fetched');
        }

        $logger->info('Generating sitemap: ' . $name);
        $this->generator->setLogger($logger);
        try {
            $sitemapUrl = $this->generator->generate($name, $linkCollection);
        } catch (\Exception $e) {
            $logger->err($e->getMessage());
            return new SitemapResult(false, $e->getMessage());
        }
        $logger->info('Generated sitemap: ' . $name . ' -> ' . $sitemapUrl);
        return new SitemapResult(true, 'Generated: ' . $name . ' -> ' . $sitemapUrl);
    }

    public function ping(string $name): SitemapResult
    {
        $url = $this->generator->getOptions()->withName($name)->getSitemapUrl();
        $client = new Client('http://www.google.com/ping');
        $client->setParameterGet(['sitemap' => $url]);
        $response = $client->send();
        preg_match('~GET\s([^\s]+).*Host:\s([^\s]+)~is', $client->getLastRawRequest(), $matches);
        $this->getLogger()->info('Ping: ' . $matches[2] . urldecode($matches[1]));

        if (!$response->isOk()) {
            $this->getLogger()->err('Ping failed: ' . $response->getReasonPhrase());
            return new SitemapResult(false, 'Ping failed: ' . $response->getReasonPhrase());
        }

        $this->getLogger()->info('Pinged successfully');
        return new SitemapResult(true, 'Pinged: ' . $matches[2] . urldecode($matches[1]));
    }
}
