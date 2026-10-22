<?php

namespace App\Http\Controllers;

use App\Models\Deployment;
use Illuminate\Http\Request;

class DeploymentSimulatorController extends Controller
{
    /**
     * Display the Live Deployment Simulator & Pipeline Visualizer
     */
    public function index()
    {
        $recentDeployments = Deployment::latest('deployment_date')->take(5)->get();

        $defaultServers = [
            'Ubuntu VPS 24.04 (Primary Production)',
            'AWS EC2 Staging (ap-south-1)',
            'DigitalOcean Droplet (Bangalore)',
            'GCP Compute Engine (us-central1)',
        ];

        $projects = [
            'PHP_Laravel12_Deploye_To_Server',
            'Ecommerce_API_Gateway',
            'Customer_Portal_V2',
            'Authentication_SSO_Service',
        ];

        return view('deployment-simulator', compact('recentDeployments', 'defaultServers', 'projects'));
    }

    /**
     * Execute Simulated 6-Stage Deployment Pipeline
     */
    public function execute(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string',
            'server_name' => 'required|string',
            'environment' => 'required|string',
            'deployed_by' => 'nullable|string',
            'fail_stage' => 'nullable|string', // git, composer, npm, migrate, cache, none
        ]);

        $project = $request->project_name;
        $server = $request->server_name;
        $env = $request->environment;
        $deployedBy = $request->deployed_by ?: 'DevOps Operator';
        $failStage = $request->fail_stage ?: 'none';

        $commitHash = substr(md5(uniqid()), 0, 7);
        $totalDuration = 0;
        $stages = [];

        // Definition of the 6 pipeline stages
        $stageDefinitions = [
            [
                'id' => 'git',
                'name' => 'Git Fetch & Checkout',
                'command' => 'git fetch origin main && git checkout main && git pull',
                'base_duration' => 3.2,
                'logs' => [
                    "From github.com/Excelsior-Technologies-Community/{$project}",
                    "* branch            main       -> FETCH_HEAD",
                    "Updating a4f18c2..{$commitHash}",
                    "Fast-forward",
                    " app/Http/Controllers/ServerController.php | 14 +++++",
                    " routes/web.php                            |  6 +++",
                    " 2 files changed, 20 insertions(+)",
                ],
            ],
            [
                'id' => 'composer',
                'name' => 'Composer Production Dependencies',
                'command' => 'composer install --no-dev --prefer-dist --optimize-autoloader',
                'base_duration' => 6.8,
                'logs' => [
                    "Installing dependencies from lock file",
                    "Verifying lock file integrity...",
                    "Package operations: 0 installs, 2 updates, 0 removals",
                    "  - Upgrading laravel/framework (v12.48.0 => v12.49.0)",
                    "Generating optimized autoload files containing 6,310 classes",
                    "> @php artisan package:discover --ansi",
                ],
            ],
            [
                'id' => 'npm',
                'name' => 'NPM Asset Compilation',
                'command' => 'npm ci && npm run build',
                'base_duration' => 8.4,
                'logs' => [
                    "added 64 packages in 2.14s",
                    "> vite build",
                    "vite v6.1.0 building for production...",
                    "✓ 48 modules transformed.",
                    "public/build/manifest.json              1.12 kB",
                    "public/build/assets/app-C1a2B3d4.css    32.40 kB │ gzip: 6.20 kB",
                    "public/build/assets/app-D5e6F7g8.js     84.15 kB │ gzip: 26.85 kB",
                    "✓ built in 1.42s",
                ],
            ],
            [
                'id' => 'migrate',
                'name' => 'Database Migration Execution',
                'command' => 'php artisan migrate --force --isolated',
                'base_duration' => 4.1,
                'logs' => [
                    "Running migrations on environment [{$env}]...",
                    "Acquiring database isolation lock...",
                    "Migrating: 2026_10_22_043126_add_duration_and_commit_to_deployments_table",
                    "Migrated:  2026_10_22_043126_add_duration_and_commit_to_deployments_table (42.15ms)",
                    "Nothing else to migrate.",
                ],
            ],
            [
                'id' => 'cache',
                'name' => 'Optimization & Cache Warming',
                'command' => 'php artisan config:cache && php artisan route:cache && php artisan view:cache',
                'base_duration' => 3.5,
                'logs' => [
                    "Configuration cached successfully.",
                    "Routes cached successfully (28 routes).",
                    "Blade views compiled and cached successfully (16 views).",
                    "Event listeners cached successfully.",
                ],
            ],
            [
                'id' => 'live',
                'name' => 'Service Reload & Health Verification',
                'command' => 'sudo systemctl reload php8.2-fpm && sudo systemctl reload nginx && curl -f http://127.0.0.1/health-check',
                'base_duration' => 2.8,
                'logs' => [
                    "Reloading PHP-FPM socket unix:/run/php/php8.2-fpm.sock...",
                    "Nginx configuration syntax: ok",
                    "Nginx service reloaded smoothly with zero downtime.",
                    "Health probe: GET /health-check => HTTP 200 OK (latency: 18ms)",
                    "DEPLOYMENT COMPLETED SUCCESSFULLY 🚀",
                ],
            ],
        ];

        $overallSuccess = true;
        $failedStageName = '';

        foreach ($stageDefinitions as $def) {
            $stageId = $def['id'];
            $isFailureThisStage = ($failStage === $stageId);

            $duration = round($def['base_duration'] + (rand(-4, 6) / 10), 2);
            $totalDuration += $duration;

            if ($isFailureThisStage) {
                $overallSuccess = false;
                $failedStageName = $def['name'];
                $errorLogs = array_merge($def['logs'], [
                    "FATAL ERROR: Execution failed with exit status code 1",
                    "Stderr: Process terminated unexpectedly during [{$def['name']}]",
                    "Deployment halted. Initiating rollback safe state.",
                ]);

                $stages[] = [
                    'id' => $stageId,
                    'name' => $def['name'],
                    'command' => $def['command'],
                    'duration' => $duration,
                    'status' => 'failed',
                    'logs' => $errorLogs,
                ];
                break; // Stop further stages
            } else {
                $stages[] = [
                    'id' => $stageId,
                    'name' => $def['name'],
                    'command' => $def['command'],
                    'duration' => $duration,
                    'status' => 'success',
                    'logs' => $def['logs'],
                ];
            }
        }

        $statusStr = $overallSuccess ? 'Success' : 'Failed';
        $remarks = $overallSuccess
            ? "Automated live simulation deployment (commit: {$commitHash}, 6/6 stages passed)"
            : "Deployment failed at stage [{$failedStageName}] (commit: {$commitHash})";

        // Store into deployments database table
        $deployment = Deployment::create([
            'project_name' => $project,
            'server_name' => $server,
            'environment' => $env,
            'status' => $statusStr,
            'duration_seconds' => (int) round($totalDuration),
            'commit_hash' => $commitHash,
            'deployed_by' => $deployedBy,
            'deployment_date' => now(),
            'remarks' => $remarks,
        ]);

        return response()->json([
            'success' => true,
            'overall_status' => $statusStr,
            'total_duration' => round($totalDuration, 1),
            'commit_hash' => $commitHash,
            'deployment_id' => $deployment->id,
            'stages' => $stages,
            'message' => $overallSuccess
                ? "Deployment #{$deployment->id} to {$server} completed successfully in {$totalDuration}s!"
                : "Deployment #{$deployment->id} failed during {$failedStageName}!",
        ]);
    }
}
