<?php
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Sitemap\Queue;

use Core\Queue\Job\MongoJob;
use Core\Queue\LoggerAwareJobTrait;
use Zend\Log\LoggerAwareInterface;
use Core\EventManager\EventManager;
use Sitemap\Event\GenerateSitemapEvent;
use Sitemap\Generator\SitemapGenerator;
use SlmQueue\Queue\QueueAwareInterface;
use SlmQueue\Queue\QueueAwareTrait;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class GenerateSitemapJob extends MongoJob implements
    LoggerAwareInterface,
    QueueAwareInterface
{
    use LoggerAwareJobTrait;
    use QueueAwareTrait;

    /** @var EventManager */
    private $events;

    protected $content = [];

    public function __construct(EventManager $events, SitemapGenerator $generator)
    {
        $this->events = $events;
        $this->generator = $generator;
    }

    public function getPayload(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->content;
        }

        if (!is_array($this->content) || !array_key_exists($key, $this->content)) {
            return $default;
        }

        return $this->content[$key];
    }

    public function setPayload(string $key, $value)
    {
        $this->content[$key] = $value;
    }

    public function execute()
    {
        $logger = $this->getLogger();
        $name = $this->getPayload('name');
        $logger->info('Fetching links for sitemap: ' . $name);
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
            return $this->failure('No links were fetched.');
        }

        $logger->info('Generating sitemap: ' . $name);
        $this->generator->setLogger($logger);
        try {
            $sitemapUrl = $this->generator->generate($name, $linkCollection);
        } catch (\Exception $e) {
            $logger->err($e->getMessage());

            return $this->failure(
                $e->getMessage(),
                [
                    'type' => get_class($e),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
        }
        $logger->info('Generated sitemap: ' . $name . ' -> ' . $sitemapUrl);
        $this->getQueue()->push(PingGoogleJob::create($sitemapUrl));

        return $this->success();
    }
}
