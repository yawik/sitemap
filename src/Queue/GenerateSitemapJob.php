<?php declare(strict_types=1);
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Sitemap\Queue;

use Core\Queue\Job\MongoJob;
use Core\Queue\LoggerAwareJobTrait;
use Sitemap\Service\Sitemap;
use SlmQueue\Queue\QueueAwareInterface;
use SlmQueue\Queue\QueueAwareTrait;
use Laminas\Log\LoggerAwareInterface;

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

    /** @var Sitemap */
    private $sitemap;
    protected $content = [];

    public function __construct(Sitemap $sitemap)
    {
        $this->sitemap = $sitemap;
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
        $this->sitemap->setLogger($this->getLogger());
        $result = $this->sitemap->generate($this->getPayload('name'));

        if ($result->isSuccess()) {
            return $this->success();
        }

        return $this->failure($result->getMessage());
    }
}
