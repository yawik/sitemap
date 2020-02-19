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
use Laminas\Http\Client;
use Sitemap\Service\Sitemap;
use Laminas\Log\LoggerAwareInterface;
use Core\Queue\LoggerAwareJobTrait;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class PingGoogleJob extends MongoJob implements LoggerAwareInterface
{
    use LoggerAwareJobTrait;

    /** @var Sitemap */
    private $sitemap;

    public function __construct(Sitemap $sitemap)
    {
        $this->sitemap = $sitemap;
    }

    public function execute()
    {
        $this->sitemap->setLogger($this->getLogger());
        $result = $this->sitemap->ping($this->getContent());

        if ($result->isSuccess()) {
            return $this->success();
        }

        return $this->failure($result->getMessage());
    }
}
