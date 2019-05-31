<?php
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Sitemap\Controller\Plugin;

use Sitemap\Queue\GenerateSitemapJob;
use SlmQueue\Controller\Plugin\QueuePlugin;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class Sitemap extends AbstractPlugin
{
    /** @var QueuePlugin */
    private $queuePlugin;

    public function __construct(QueuePlugin $queuePlugin)
    {
        $this->queuePlugin = $queuePlugin;
    }

    public function __invoke(string $name): void
    {
        $job = GenerateSitemapJob::create(['name' => $name]);
        ($this->queuePlugin)('sitemap')->pushJob($job);
    }
}
