<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel 12 Deployment Hub & DevOps Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #0f172a;
            color: #f8fafc;
            font-family: system-ui, -apple-system, sans-serif;
        }

        .hero {
            background: linear-gradient(135deg, #1e3a8a, #4338ca);
            color: white;
            border-radius: 18px;
            border: 1px solid #3b82f6;
        }

        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 15px;
            color: #f8fafc;
            transition: .3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, .4);
            border-color: #38bdf8;
        }

        .feature-box {
            padding: 30px;
            text-align: center;
            height: 100%;
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 28px;
            color: white;
            margin: 0 auto 20px auto;
        }

        .table td, .table th {
            color: #f8fafc;
            border-color: #334155;
            background: transparent;
        }
    </style>
</head>

<body>
    @include('layouts.navigation')

    <div class="container py-2">

        <!-- Hero Header -->
        <div class="hero p-5 shadow mb-5">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold mb-2">
                        <i class="fa-solid fa-server me-2"></i>
                        Laravel 12 Deployment & DevOps Control Center
                    </h1>
                    <p class="lead mb-0 text-light opacity-90">
                        End-to-end continuous delivery pipeline, interactive simulators, telemetry analytics, and Linux SSH terminal.
                    </p>
                </div>
                <div>
                    <span class="badge bg-success px-3 py-2 fs-6"><i class="fa-solid fa-circle-check"></i> System Operational</span>
                </div>
            </div>

            <hr class="border-secondary opacity-50 my-4">

            <div class="row text-center g-3">
                <div class="col-md-3">
                    <h4 class="fw-bold text-info">{{ app()->version() }}</h4>
                    <small class="text-light opacity-75">Laravel Version</small>
                </div>
                <div class="col-md-3">
                    <h4 class="fw-bold text-info">{{ PHP_VERSION }}</h4>
                    <small class="text-light opacity-75">PHP Runtime</small>
                </div>
                <div class="col-md-3">
                    <h4 class="fw-bold text-info">{{ config('app.env') }}</h4>
                    <small class="text-light opacity-75">Environment Mode</small>
                </div>
                <div class="col-md-3">
                    <h4 class="fw-bold text-warning">Active</h4>
                    <small class="text-light opacity-75">Deployment Ready</small>
                </div>
            </div>
        </div>

        <!-- 4 Key Core Features Grid -->
        <h4 class="text-light fw-bold mb-3"><i class="fa-solid fa-cubes-stacked text-primary me-2"></i> DevOps & Deployment Engine Modules</h4>
        <div class="row g-4 mb-5">
            <!-- Feature 1: One-Click Deployment Simulator -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-body feature-box">
                        <div class="icon-circle bg-warning text-dark">
                            <i class="fa-solid fa-rocket"></i>
                        </div>
                        <h4 class="fw-bold text-light">One-Click Deployment Simulator</h4>
                        <p class="text-secondary mt-2">
                            Interactive 6-stage animated continuous delivery pipeline (Git Pull → Composer → Vite Build → DB Migrate → Cache Warm → Go Live) with live stdout log streaming.
                        </p>
                        <a href="{{ route('deployment.simulator') }}" class="btn btn-warning fw-bold px-4 mt-2">
                            <i class="fa-solid fa-play me-1"></i> Launch Deployment Simulator
                        </a>
                    </div>
                </div>
            </div>

            <!-- Feature 2: Analytics & Trends Dashboard -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-body feature-box">
                        <div class="icon-circle bg-info text-dark">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h4 class="fw-bold text-light">Deployment Analytics & Trends</h4>
                        <p class="text-secondary mt-2">
                            Visual Chart.js dashboard tracking 30-day deployment volume, failure pattern heatmaps, environment distribution shares, and top deployer leaderboards.
                        </p>
                        <a href="{{ route('deployment.analytics') }}" class="btn btn-info text-dark fw-bold px-4 mt-2">
                            <i class="fa-solid fa-chart-pie me-1"></i> View Analytics Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Feature 3: GitHub Actions CI/CD Workflow Builder -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-body feature-box">
                        <div class="icon-circle bg-primary">
                            <i class="fa-brands fa-github"></i>
                        </div>
                        <h4 class="fw-bold text-light">GitHub Actions CI/CD Builder</h4>
                        <p class="text-secondary mt-2">
                            Visual drag-and-drop workflow architect to generate production-ready <code>ci.yml</code> files with PHP version gating, automated testing, and remote SSH deployment.
                        </p>
                        <a href="{{ route('cicd.builder') }}" class="btn btn-primary fw-bold px-4 mt-2">
                            <i class="fa-solid fa-code me-1"></i> Open CI/CD Builder
                        </a>
                    </div>
                </div>
            </div>

            <!-- Feature 7: Server SSH Terminal Simulator -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-body feature-box">
                        <div class="icon-circle bg-success">
                            <i class="fa-solid fa-terminal"></i>
                        </div>
                        <h4 class="fw-bold text-light">Server SSH Terminal Simulator</h4>
                        <p class="text-secondary mt-2">
                            Realistic browser-based Ubuntu terminal emulator to execute commands (ssh, git, composer, artisan, systemctl, chmod) with a comprehensive Command Reference Lab.
                        </p>
                        <a href="{{ route('server.terminal') }}" class="btn btn-success fw-bold px-4 mt-2">
                            <i class="fa-solid fa-terminal me-1"></i> Open Terminal Lab
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- System & Historical Diagnostics Row -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow text-center p-4">
                    <i class="fa-solid fa-cloud-arrow-up text-primary fa-2x mb-3"></i>
                    <h5 class="fw-bold">Deployment Manager</h5>
                    <p class="text-secondary small">Browse, search, edit, and export complete deployment audit logs in CSV format.</p>
                    <a href="{{ route('deployments.index') }}" class="btn btn-sm btn-outline-primary mt-auto">Open Manager</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow text-center p-4">
                    <i class="fa-solid fa-microchip text-info fa-2x mb-3"></i>
                    <h5 class="fw-bold">Server Architecture</h5>
                    <p class="text-secondary small">Inspect PHP extensions, memory limits, opcache settings, and operating system specs.</p>
                    <a href="{{ route('server.info') }}" class="btn btn-sm btn-outline-info mt-auto">Inspect Architecture</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow text-center p-4">
                    <i class="fa-solid fa-heart-pulse text-success fa-2x mb-3"></i>
                    <h5 class="fw-bold">Pre-Flight Health Check</h5>
                    <p class="text-secondary small">Verify database connectivity, storage writable permissions, and application encryption keys.</p>
                    <a href="{{ route('health.check') }}" class="btn btn-sm btn-outline-success mt-auto">Run Health Check</a>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
