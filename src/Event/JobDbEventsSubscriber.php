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

use Core\Queue\MongoQueue;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\PostFlushEventArgs;
use Doctrine\ODM\MongoDB\Events;
use Jobs\Entity\Job;
use Sitemap\Queue\GenerateSitemapJob;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class JobDbEventsSubscriber implements EventSubscriber
{
    /** @var MongoQueue|null */
    private $queue;
    /** @var callable */
    private $queueFactory;
    private $mustEnqueue = false;

    public function __construct(callable $queueFactory)
    {
        $this->queueFactory = $queueFactory;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
            Events::postFlush,
        ];
    }

    private function mustEnqueue(LifecycleEventArgs $args): void
    {
        $this->mustEnqueue =
            $this->mustEnqueue
            || $args->getDocument() instanceof Job
        ;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->mustEnqueue($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->mustEnqueue($args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->mustEnqueue($args);
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        if (!$this->mustEnqueue) {
            return;
        }

        $queue = $this->getQueue();
        $queue->push(GenerateSitemapJob::create(['name' => 'jobs']));
        $this->mustEnqueue = false;
    }

    private function getQueue(): MongoQueue
    {
        if (!$this->queue) {
            $this->queue = ($this->queueFactory)();
        }

        return $this->queue;
    }
}
