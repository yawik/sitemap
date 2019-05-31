<?php
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Sitemap;

use Yawik\Composer\RequireDirectoryPermissionInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Sitemap\Controller\ConsoleController;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class Module implements
    ConfigProviderInterface,
    RequireDirectoryPermissionInterface,
    ConsoleBannerProviderInterface,
    ConsoleUsageProviderInterface
{
    public function getRequiredDirectoryLists(\Core\Options\ModuleOptions $options)
    {
        return [
            $options->getPublicDir() . '/static/sitemaps'
        ];
    }

    public function getConsoleBanner(\Zend\Console\Adapter\AdapterInterface $console)
    {
        return __NAMESPACE__ . ' 0.1';
    }

    public function getConsoleUsage(\Zend\Console\Adapter\AdapterInterface $console)
    {
        return ConsoleController::getConsoleUsage();
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
