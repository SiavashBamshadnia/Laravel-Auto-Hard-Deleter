<?php

/**
 * Laravel Auto Hard Deleter
 *
 * @author      Siavash Bamshadnia
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        https://github.com/SiavashBamshadnia/Laravel-Auto-Hard-Deleter
 */

namespace sbamtr\LaravelAutoHardDeleter;

use Illuminate\Support\ServiceProvider;

class AutoHardDeleteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register hard-delete-expired artisan command
        $this->commands([
            HardDeleteExpiredCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config file
        $configPath = __DIR__ . '/../config/auto-hard-deleter.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('auto-hard-deleter.php');
        } else if (function_exists('base_path')) {
            $publishPath = base_path('config/auto-hard-deleter.php');
        }
        $this->publishes([$configPath => $publishPath], 'config');
    }
}
