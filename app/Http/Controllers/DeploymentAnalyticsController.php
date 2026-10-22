<?php

namespace App\Http\Controllers;

use App\Models\Deployment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeploymentAnalyticsController extends Controller
{
    /**
     * Display Deployment Analytics & Success Rate Trend Dashboard
     */
    public function index(Request $request)
    {
        $days = (int) $request->query('days', 30);

        $totalDeployments = Deployment::count();
        $successCount = Deployment::where('status', 'Success')->count();
        $failedCount = Deployment::where('status', 'Failed')->count();
        $successRate = $totalDeployments > 0 ? round(($successCount / $totalDeployments) * 100, 1) : 0;

        $avgDuration = round((float) (Deployment::whereNotNull('duration_seconds')->avg('duration_seconds') ?? 45), 1);
        $weeklyAverage = round((float) ($totalDeployments / 4.2), 1);

        $topDeployer = Deployment::select('deployed_by', DB::raw('count(*) as count'))
            ->groupBy('deployed_by')
            ->orderByDesc('count')
            ->first();

        $mostDeployedProject = Deployment::select('project_name', DB::raw('count(*) as count'))
            ->groupBy('project_name')
            ->orderByDesc('count')
            ->first();

        // 1. Line Chart Data: Daily Deployment counts for the last 30 days
        $startDate = now()->subDays($days);
        $timelineRecords = Deployment::where('deployment_date', '>=', $startDate)
            ->select(DB::raw('DATE(deployment_date) as date'), DB::raw('count(*) as count'), DB::raw('SUM(CASE WHEN status = "Success" THEN 1 ELSE 0 END) as success_count'), DB::raw('SUM(CASE WHEN status = "Failed" THEN 1 ELSE 0 END) as failed_count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $timelineLabels = [];
        $timelineSuccess = [];
        $timelineFailed = [];

        // Build continuous date array
        for ($i = $days; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $record = $timelineRecords->firstWhere('date', $d);
            $timelineLabels[] = date('M d', strtotime($d));
            $timelineSuccess[] = $record ? (int) $record->success_count : 0;
            $timelineFailed[] = $record ? (int) $record->failed_count : 0;
        }

        // 2. Environment Distribution (Pie / Doughnut)
        $envStats = Deployment::select('environment', DB::raw('count(*) as count'))
            ->groupBy('environment')
            ->pluck('count', 'environment')
            ->toArray();

        // 3. Top Deployers Leaderboard (Bar Chart)
        $deployerStats = Deployment::select('deployed_by', DB::raw('count(*) as count'))
            ->groupBy('deployed_by')
            ->orderByDesc('count')
            ->take(5)
            ->pluck('count', 'deployed_by')
            ->toArray();

        // 4. Server Distribution
        $serverStats = Deployment::select('server_name', DB::raw('count(*) as count'))
            ->groupBy('server_name')
            ->orderByDesc('count')
            ->pluck('count', 'server_name')
            ->toArray();

        // Recent Deployments
        $recentDeployments = Deployment::latest('deployment_date')->take(10)->get();

        return view('deployment-analytics', compact(
            'totalDeployments',
            'successCount',
            'failedCount',
            'successRate',
            'avgDuration',
            'weeklyAverage',
            'topDeployer',
            'mostDeployedProject',
            'timelineLabels',
            'timelineSuccess',
            'timelineFailed',
            'envStats',
            'deployerStats',
            'serverStats',
            'recentDeployments',
            'days'
        ));
    }
}
