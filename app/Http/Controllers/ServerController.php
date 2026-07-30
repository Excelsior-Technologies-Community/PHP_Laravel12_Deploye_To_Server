<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ServerController extends Controller
{
    /**
     * Display server information.
     */
    public function serverInfo()
    {
        $serverInfo = [
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'environment' => config('app.env'),
            'app_debug' => config('app.debug') ? 'Enabled' : 'Disabled',
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'database' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time') . ' seconds',
            'server_time' => now()->format('d M Y h:i:s A'),
            'operating_system' => PHP_OS_FAMILY,
        ];

        return view('server-info', compact('serverInfo'));
    }

    /**
     * Deployment health check.
     */
    public function healthCheck()
    {
        $checks = [];

        // Database Connection
        try {
            DB::connection()->getPdo();
            $checks['Database Connection'] = true;
        } catch (\Exception $e) {
            $checks['Database Connection'] = false;
        }

        // APP_KEY
        $checks['Application Key'] = !empty(config('app.key'));

        // Storage Folder
        $checks['Storage Writable'] = is_writable(storage_path());

        // Bootstrap Cache
        $checks['Bootstrap Cache Writable'] = is_writable(base_path('bootstrap/cache'));

        // Cache Driver
        $checks['Cache Configured'] = !empty(config('cache.default'));

        // Session Driver
        $checks['Session Configured'] = !empty(config('session.driver'));

        // Queue Driver
        $checks['Queue Configured'] = !empty(config('queue.default'));

        // Logs Folder
        $checks['Logs Directory Exists'] = File::exists(storage_path('logs'));

        // Public Folder
        $checks['Public Directory Exists'] = File::exists(public_path());

        // PHP Version
        $checks['PHP >= 8.2'] = version_compare(PHP_VERSION, '8.2.0', '>=');

        return view('health-check', compact('checks'));
    }
}