<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deployment Analytics & Trend Dashboard - Laravel 12</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .kpi-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 14px;
            padding: 22px;
            transition: all 0.25s ease;
        }
        .kpi-card:hover {
            border-color: #38bdf8;
            box-shadow: 0 10px 25px rgba(56, 189, 248, 0.1);
        }
        .chart-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 14px;
            padding: 20px;
        }
        .table-custom th {
            background: #1e293b;
            color: #94a3b8;
            font-size: 11px;
            text-transform: uppercase;
            border-color: #334155;
        }
        .table-custom td {
            background: #0f172a;
            color: #f1f5f9;
            border-color: #334155;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    @include('layouts.navigation')

    <div class="container py-2">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-light mb-1">
                    <i class="fa-solid fa-chart-line text-info"></i> Deployment Analytics & Success Rate Trends
                </h3>
                <p class="text-secondary mb-0">Historical observability, failure pattern analytics, and continuous delivery throughput metrics</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('deployment.analytics', ['days' => 7]) }}" class="btn btn-sm {{ $days == 7 ? 'btn-primary' : 'btn-outline-secondary text-light' }}">Last 7 Days</a>
                <a href="{{ route('deployment.analytics', ['days' => 30]) }}" class="btn btn-sm {{ $days == 30 ? 'btn-primary' : 'btn-outline-secondary text-light' }}">Last 30 Days</a>
                <a href="{{ route('deployment.analytics', ['days' => 90]) }}" class="btn btn-sm {{ $days == 90 ? 'btn-primary' : 'btn-outline-secondary text-light' }}">Last 90 Days</a>
            </div>
        </div>

        <!-- 4 Top KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="kpi-card">
                    <span class="text-secondary small fw-bold"><i class="fa-solid fa-layer-group text-primary"></i> TOTAL DEPLOYMENTS</span>
                    <div class="fs-2 fw-bold text-light mt-1">{{ number_format($totalDeployments) }}</div>
                    <span class="text-muted small">Recorded execution runs</span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="kpi-card">
                    <span class="text-secondary small fw-bold"><i class="fa-solid fa-circle-check text-success"></i> GLOBAL SUCCESS RATE</span>
                    <div class="fs-2 fw-bold {{ $successRate >= 85 ? 'text-success' : 'text-warning' }} mt-1">{{ $successRate }}%</div>
                    <span class="text-muted small">{{ $successCount }} succeeded / {{ $failedCount }} failed</span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="kpi-card">
                    <span class="text-secondary small fw-bold"><i class="fa-solid fa-stopwatch text-warning"></i> MEAN DURATION</span>
                    <div class="fs-2 fw-bold text-light mt-1">{{ $avgDuration }}s</div>
                    <span class="text-muted small">Pipeline cycle execution time</span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="kpi-card">
                    <span class="text-secondary small fw-bold"><i class="fa-solid fa-crown text-warning"></i> TOP DEPLOYER</span>
                    <div class="fs-4 fw-bold text-info mt-1 text-truncate">{{ $topDeployer ? $topDeployer->deployed_by : 'DevOps Bot' }}</div>
                    <span class="text-muted small">{{ $topDeployer ? $topDeployer->count : 0 }} deployments delivered</span>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-3 mb-4">
            <!-- 30-Day Timeline Chart -->
            <div class="col-lg-8">
                <div class="chart-card shadow h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 text-light fw-bold"><i class="fa-solid fa-chart-area text-primary"></i> Deployment Frequency Trend (Last {{ $days }} Days)</h6>
                        <span class="badge bg-secondary">Daily Volume</span>
                    </div>
                    <div style="height: 280px;">
                        <canvas id="timelineChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Environment Distribution Doughnut Chart -->
            <div class="col-lg-4">
                <div class="chart-card shadow h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 text-light fw-bold"><i class="fa-solid fa-chart-pie text-success"></i> Environment Share</h6>
                        <span class="badge bg-secondary">Target Matrix</span>
                    </div>
                    <div style="height: 280px; position: relative;">
                        <canvas id="envChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row of Charts & Top Deployers -->
        <div class="row g-3 mb-4">
            <!-- Top Deployers Bar Chart -->
            <div class="col-lg-6">
                <div class="chart-card shadow">
                    <h6 class="mb-3 text-light fw-bold"><i class="fa-solid fa-ranking-star text-warning"></i> Top Deployers Leaderboard</h6>
                    <div style="height: 220px;">
                        <canvas id="deployersChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Server Infrastructure Distribution -->
            <div class="col-lg-6">
                <div class="chart-card shadow">
                    <h6 class="mb-3 text-light fw-bold"><i class="fa-solid fa-server text-info"></i> Server Infrastructure Target Volume</h6>
                    <div class="table-responsive">
                        <table class="table table-dark table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Server Node</th>
                                    <th class="text-end">Deployments</th>
                                    <th class="text-end">Share</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serverStats as $srv => $cnt)
                                    <tr>
                                        <td><i class="fa-solid fa-network-wired text-secondary me-1"></i> {{ $srv }}</td>
                                        <td class="text-end fw-bold text-info">{{ $cnt }}</td>
                                        <td class="text-end text-muted">{{ $totalDeployments > 0 ? round(($cnt / $totalDeployments) * 100) : 0 }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Deployments Audit Table -->
        <div class="card bg-dark border-secondary shadow mb-4">
            <div class="card-header border-secondary d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 text-light fw-bold"><i class="fa-solid fa-history text-secondary"></i> Recent Audit Log History</h6>
                <a href="{{ route('deployments.export') }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-file-csv"></i> Export CSV</a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Project</th>
                            <th>Server Node</th>
                            <th>Environment</th>
                            <th>Commit</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Deployed By</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDeployments as $dep)
                            <tr>
                                <td>#{{ $dep->id }}</td>
                                <td class="fw-semibold text-light">{{ $dep->project_name }}</td>
                                <td><span class="badge bg-dark border border-secondary text-info">{{ $dep->server_name }}</span></td>
                                <td>
                                    <span class="badge {{ $dep->environment === 'Production' ? 'bg-danger' : ($dep->environment === 'Staging' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                        {{ $dep->environment }}
                                    </span>
                                </td>
                                <td><code>{{ $dep->commit_hash ?: 'manual' }}</code></td>
                                <td>{{ $dep->duration_seconds ? $dep->duration_seconds . 's' : '--' }}</td>
                                <td>
                                    <span class="badge {{ $dep->status === 'Success' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $dep->status }}
                                    </span>
                                </td>
                                <td class="text-secondary small">{{ $dep->deployed_by }}</td>
                                <td class="text-muted small">{{ $dep->deployment_date ? $dep->deployment_date->format('M d, H:i') : '--' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No deployment records available yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 1. Timeline Chart (Line)
        const ctxTimeline = document.getElementById('timelineChart').getContext('2d');
        new Chart(ctxTimeline, {
            type: 'line',
            data: {
                labels: {!! json_encode($timelineLabels) !!},
                datasets: [
                    {
                        label: 'Successful Deployments',
                        data: {!! json_encode($timelineSuccess) !!},
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.15)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2
                    },
                    {
                        label: 'Failed Deployments',
                        data: {!! json_encode($timelineFailed) !!},
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.15)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } },
                    y: { ticks: { color: '#94a3b8', stepSize: 1 }, grid: { color: '#334155' } }
                },
                plugins: {
                    legend: { labels: { color: '#f8fafc' } }
                }
            }
        });

        // 2. Environment Share (Doughnut)
        const ctxEnv = document.getElementById('envChart').getContext('2d');
        new Chart(ctxEnv, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($envStats)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($envStats)) !!},
                    backgroundColor: ['#ef4444', '#f59e0b', '#0ea5e9', '#64748b'],
                    borderWidth: 2,
                    borderColor: '#1e293b'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#f8fafc' } }
                }
            }
        });

        // 3. Top Deployers (Bar)
        const ctxDeployers = document.getElementById('deployersChart').getContext('2d');
        new Chart(ctxDeployers, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($deployerStats)) !!},
                datasets: [{
                    label: 'Deployments',
                    data: {!! json_encode(array_values($deployerStats)) !!},
                    backgroundColor: '#38bdf8',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { display: false } },
                    y: { ticks: { color: '#94a3b8', stepSize: 2 }, grid: { color: '#334155' } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
</body>
</html>
