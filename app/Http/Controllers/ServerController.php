<?php

namespace App\Http\Controllers;

use App\Models\Deployment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function deploymentList(Request $request)
    {
        $search = $request->search;

        $deployments = Deployment::query()

            ->when($search, function ($query) use ($search) {

                $query->where('project_name', 'like', "%{$search}%")
                    ->orWhere('server_name', 'like', "%{$search}%")
                    ->orWhere('environment', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('deployed_by', 'like', "%{$search}%");
            })

            ->oldest()

            ->paginate(5)

            ->withQueryString();

        $statistics = [

            'total' => Deployment::count(),

            'success' => Deployment::where('status', 'Success')->count(),

            'failed' => Deployment::where('status', 'Failed')->count(),

            'today' => Deployment::whereDate('deployment_date', today())->count(),

        ];

        return view('deployments.index', compact(
            'deployments',
            'statistics',
            'search'
        ));
    }

    public function createDeployment()
    {
        return view('deployments.create');
    }

    public function storeDeployment(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'server_name' => 'required|string|max:255',
            'environment' => 'required|string|max:100',
            'status' => 'required|string|in:Success,Failed',
            'deployed_by' => 'required|string|max:255',
            'deployment_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $validated['duration_seconds'] = rand(25, 95);
        $validated['commit_hash'] = substr(md5(uniqid()), 0, 7);

        Deployment::create($validated);

        return redirect()
            ->route('deployments.index')
            ->with('success', 'Deployment logged successfully.');
    }

    public function editDeployment(Deployment $deployment)
    {
        return view('deployments.edit', compact('deployment'));
    }

    public function updateDeployment(Request $request, Deployment $deployment)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'server_name' => 'required|string|max:255',
            'environment' => 'required|string|max:100',
            'status' => 'required|string|in:Success,Failed',
            'deployed_by' => 'required|string|max:255',
            'deployment_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $deployment->update($validated);

        return redirect()
            ->route('deployments.index')
            ->with('success', 'Deployment updated successfully.');
    }

    public function deleteDeployment(Deployment $deployment)
    {
        $deployment->delete();

        return redirect()
            ->route('deployments.index')
            ->with('success', 'Deployment deleted successfully.');
    }

    public function exportCsv()
    {
        $fileName = 'deployments.csv';

        $deployments = Deployment::all();

        $headers = [

            "Content-type" => "text/csv",

            "Content-Disposition" => "attachment; filename={$fileName}",

        ];

        $columns = [

            'ID',

            'Project',

            'Server',

            'Environment',

            'Status',

            'Deployed By',

            'Deployment Date',

            'Remarks',

        ];

        $callback = function () use ($deployments, $columns) {

            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            foreach ($deployments as $deployment) {

                fputcsv($file, [

                    $deployment->id,

                    $deployment->project_name,

                    $deployment->server_name,

                    $deployment->environment,

                    $deployment->status,

                    $deployment->deployed_by,

                    $deployment->deployment_date,

                    $deployment->remarks,

                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
