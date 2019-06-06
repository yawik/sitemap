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
use Sitemap\Queue\GenerateSitemapJob;

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
            'sitemap generate <name>'  => 'Generates sitemap <name>',
            'sitemap ping <name>' => 'Ping google.com the sitemap url of the sitemap <name>',
            'sitemap enqueue <name>' => 'Enqueue the generation of sitemap <name>',
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

        $this->sitemap($name, $this->params('ping'));
    }

    public function enqueueAction()
    {
        $name = $this->params('name');

        $this->queue('sitemap')->pushJob(GenerateSitemapJob::create(['name' => $name]));
        echo 'Enqueued generate sitemap job.' . PHP_EOL;
    }
}
