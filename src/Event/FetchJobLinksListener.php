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

use Sitemap\Entity\RouteLink;
use samdark\sitemap\Sitemap;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class FetchJobLinksListener
{

    /** @var \Jobs\Repository\Job */
    private $repository;

    public function __construct(\Jobs\Repository\Job $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GenerateSitemapEvent $event)
    {
        $collection = $event->getLinkCollection();
        $jobs = $this->repository->findBy(['status.name' => \Jobs\Entity\StatusInterface::ACTIVE]);

        /** @var \Jobs\Entity\Job $job */
        foreach ($jobs as $job) {
            $link = new RouteLink();
            $link->setName('lang/jobs/view');
            $link->setParams(['id' => $job->getId()]);
            $link->setLastModified($job->getDateModified());
            $link->setChangeFrequency(Sitemap::DAILY);

            $collection->addLink($link);
        }
    }
}
