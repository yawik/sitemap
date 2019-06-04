<?php
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Sitemap\Controller;

use Zend\Mvc\Console\Controller\AbstractConsoleController;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class ConsoleController extends AbstractConsoleController
{
    public static function getConsoleUsage()
    {
        return [
            'Sitemap console commands',
            'sitemap list' => 'List all available sitemap generators.',
            'sitemap generate <name>'  => 'Enqueues generation of sitemap <name>',
            ''
        ];
    }

    public function listAction()
    {
        ($this->plugin(Plugin\ListSitemapGenerators::class))();
    }

    public function generateAction()
    {
        $name = $this->params('name');

        $this->sitemap($name);

        return "Enqueued generating of sitemap: $name\n\n";
    }
}
