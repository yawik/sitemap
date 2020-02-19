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
use Laminas\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Laminas\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Core\ModuleManager\Feature\VersionProviderInterface;
use Core\ModuleManager\Feature\VersionProviderTrait;
use Sitemap\Controller\ConsoleController;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

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
    ConsoleUsageProviderInterface,
    VersionProviderInterface
{
    use VersionProviderTrait;

    const VERSION = '0.4.0';

    public function getRequiredDirectoryLists(\Core\Options\ModuleOptions $options)
    {
        return [
            $options->getPublicDir() . '/static/sitemaps'
        ];
    }

    public function getConsoleBanner(\Laminas\Console\Adapter\AdapterInterface $console)
    {
        return __NAMESPACE__ . ' 0.1';
    }

    public function getConsoleUsage(\Laminas\Console\Adapter\AdapterInterface $console)
    {
        return ConsoleController::getConsoleUsage();
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
