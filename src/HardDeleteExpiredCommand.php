<?php

/**
 * Laravel Auto Hard Deleter.
 *
 * @author      Siavash Bamshadnia
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @link        https://github.com/SiavashBamshadnia/Laravel-Auto-Hard-Deleter
 */

namespace sbamtr\LaravelAutoHardDeleter;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use ReflectionClass;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class HardDeleteExpiredCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hard-delete-expired';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hard deletes expired soft deleted models';

    /**
     * Execute the console command.
     * @throws \ReflectionException
     */
    public function handle()
    {
        // Enable Eloquent in Lumen
        if (function_exists('app') && method_exists(app(), 'withEloquent')) {
            app()->withEloquent();
        }

        // Autoload classes. this is needed for finding latest model classes
        $process = new Process(['composer', 'dump-autoload', '-o']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Include all of classes
        $classes = include_once base_path('vendor/composer/autoload_classmap.php');
        $classes = array_keys($classes);
        $classes2 = [];

        // Exclude classes that not support soft delete
        foreach ($classes as $class) {
            if (Str::startsWith($class, 'App') && (new ReflectionClass($class))->hasMethod('runSoftDelete')) {
                $classes2[] = $class;
            }
        }

        foreach ($classes2 as $class) {
            $object = new $class();
            $deletedAtColumn = $object->getDeletedAtColumn();

            // If auto hard delete is not enabled, do not delete anything
            if (!defined("$class::AUTO_HARD_DELETE_ENABLED") || $class::AUTO_HARD_DELETE_ENABLED != true) {
                continue;
            }

            if (defined("$class::AUTO_HARD_DELETE_AFTER")) {
                $autoHardDeleteAfter = $class::AUTO_HARD_DELETE_AFTER;
            } else {
                $autoHardDeleteAfter = null;
            }

            if (!$autoHardDeleteAfter || blank($autoHardDeleteAfter)) {
                $autoHardDeleteAfter = config('auto-hard-deleter.auto_hard_delete_after', '60 days');
            }
            if (is_numeric($autoHardDeleteAfter)) {
                $autoHardDeleteAfter .= ' days';
            }

            // Hard delete expired rows
            $count = $class::onlyTrashed()->where($deletedAtColumn, '<=', Carbon::now()->sub($autoHardDeleteAfter))->forceDelete();

            if ($count) {
                $this->line("Deleted $count rows from ".$object->getTable().' table.');
            }
        }

        $this->info('Auto hard deleting completed successfully.');
    }
}
