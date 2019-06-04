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

use Core\EventManager\EventManager;
use Sitemap\Event\ListSitemapGeneratorsEvent;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class ListSitemapGenerators extends AbstractPlugin
{
    /** @var EventManager */
    private $events;

    public function __construct(EventManager $events)
    {
        $this->events = $events;
    }

    public function __invoke()
    {
        $event = $this->events->getEvent(
            ListSitemapGeneratorsEvent::FETCH,
            $this
        );
        $this->events->triggerEvent($event);

        echo PHP_EOL;
        foreach ($event->getList() as $name => $info) {
            $info = wordwrap($info, 40, PHP_EOL . str_repeat(' ', 25));
            $info = $name . str_repeat(' ', 25 - strlen($name)) . $info;

            echo $info . PHP_EOL . PHP_EOL;
        }
        echo PHP_EOL;
    }
}
