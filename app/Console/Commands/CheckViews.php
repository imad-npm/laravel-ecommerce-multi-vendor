<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Throwable;

class CheckViews extends Command
{
    protected $signature = 'views:check {--show-source : Show the first 5 lines of the failing view files}';
    protected $description = 'Check all Blade files for errors, ignoring undefined variable errors.';

    public function handle()
    {
        $viewPath = resource_path('views');
        if (!is_dir($viewPath)) {
            $this->error("Views path not found: {$viewPath}");
            return 1;
        }

        $files = File::allFiles($viewPath);
        $errors = [];

        foreach ($files as $file) {
            $filename = $file->getFilename();
            if (!Str::endsWith($filename, '.blade.php')) {
                continue;
            }

            $path = $file->getRealPath();
            $relative = Str::replaceFirst($viewPath . DIRECTORY_SEPARATOR, '', $path);
            $viewName = str_replace(DIRECTORY_SEPARATOR, '.', $relative);
            $viewName = preg_replace('/\.blade\.php$/', '', $viewName);

            $this->line("Checking: <info>{$relative}</info>");

            $content = File::get($path);

            // Wrap functions in views to avoid redeclare errors
            $content = preg_replace('/function\s+(\w+)\s*\(/', 'if (!function_exists("$1")) { function $1(', $content);

            // 1) Try to compile Blade
            try {
                Blade::compileString($content);
            } catch (Throwable $e) {
                $msg = $e->getMessage();
                if (!str_contains($msg, 'Undefined variable')) {
                    $errors[] = [
                        'file' => $relative,
                        'view' => $viewName,
                        'stage' => 'compile',
                        'message' => $msg,
                    ];
                    $this->error("  compile error: " . $msg);
                }
            }

            // 2) Try to render view
            try {
                view($viewName)->render();
            } catch (Throwable $e) {
                $msg = $e->getMessage();
                // Ignore undefined variable / undefined array key
                if (!str_contains($msg, 'Undefined variable') && !str_contains($msg, 'Undefined array key')) {
                    $errors[] = [
                        'file' => $relative,
                        'view' => $viewName,
                        'stage' => 'render',
                        'message' => $msg,
                    ];
                    $this->error("  render error: " . $msg);
                }
            }
        }

        if (count($errors) > 0) {
            $this->info("\nSummary of errors found: " . count($errors));
            $rows = [];
            foreach ($errors as $err) {
                $rows[] = [$err['file'], $err['view'], $err['stage'], Str::limit($err['message'], 200)];
            }
            $this->table(['file', 'view', 'stage', 'message'], $rows);
            $this->error("Fix the above issues and re-run `php artisan view:cache` to verify.");
            return 1;
        }

        $this->info("\nAll Blade files compiled and rendered (ignoring undefined variables) successfully.");
        return 0;
    }
}
