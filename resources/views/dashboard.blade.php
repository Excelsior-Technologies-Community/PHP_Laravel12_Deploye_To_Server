<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Deployment Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .hero {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: white;
            border-radius: 18px;
        }

        .card {
            border: none;
            border-radius: 15px;
            transition: .3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, .15);
        }

        .icon {
            width: 70px;
            height: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 28px;
            color: white;
            margin: auto;
        }

        .blue {
            background: #0d6efd;
        }

        .green {
            background: #198754;
        }

        .info-card {
            border-left: 5px solid #0d6efd;
        }

        footer {
            color: #6c757d;
        }
    </style>

</head>

<body>

    <div class="container py-5">

        <div class="hero p-5 shadow mb-5">

            <h1 class="fw-bold">
                <i class="fa-solid fa-server"></i>
                Laravel 12 Deployment Dashboard
            </h1>

            <p class="lead mt-3">
                Deployment to Server & Continuous Integration demonstration project.
            </p>

            <hr>

            <div class="row text-center">

                <div class="col-md-3">
                    <h4>{{ app()->version() }}</h4>
                    <small>Laravel Version</small>
                </div>

                <div class="col-md-3">
                    <h4>{{ PHP_VERSION }}</h4>
                    <small>PHP Version</small>
                </div>

                <div class="col-md-3">
                    <h4>{{ config('app.env') }}</h4>
                    <small>Environment</small>
                </div>

                <div class="col-md-3">
                    <h4 class="text-warning">
                        Ready
                    </h4>
                    <small>Deployment Status</small>
                </div>

            </div>

        </div>

        <div class="row mt-4 g-4">

            <div class="col-md-3">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <i class="fa-solid fa-layer-group text-primary fa-2x"></i>
                        <h2 class="mt-3">{{ \App\Models\Deployment::count() }}</h2>
                        <h6>Total Deployments</h6>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <i class="fa-solid fa-circle-check text-success fa-2x"></i>
                        <h2 class="mt-3">
                            {{ \App\Models\Deployment::where('status','Success')->count() }}
                        </h2>
                        <h6>Successful</h6>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <i class="fa-solid fa-circle-xmark text-danger fa-2x"></i>
                        <h2 class="mt-3">
                            {{ \App\Models\Deployment::where('status','Failed')->count() }}
                        </h2>
                        <h6>Failed</h6>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <i class="fa-solid fa-calendar-day text-warning fa-2x"></i>
                        <h2 class="mt-3">
                            {{ \App\Models\Deployment::whereDate('deployment_date',today())->count() }}
                        </h2>
                        <h6>Today's Deployments</h6>
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-4">

            <div class="col-lg-6">

                <div class="card shadow h-100">

                    <div class="card-body text-center p-5">

                        <div class="icon blue mb-4">
                            <i class="fa-solid fa-microchip"></i>
                        </div>

                        <h3>Server Information</h3>

                        <p class="text-muted mt-3">
                            View Laravel version, PHP configuration,
                            cache driver, session driver,
                            queue driver, timezone and server details.
                        </p>

                        <a href="{{ route('server.info') }}"
                            class="btn btn-primary mt-3 px-4">

                            View Server Info

                        </a>

                    </div>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="card shadow h-100">

                    <div class="card-body text-center p-5">

                        <div class="icon green mb-4">
                            <i class="fa-solid fa-heart-pulse"></i>
                        </div>

                        <h3>Deployment Health Check</h3>

                        <p class="text-muted mt-3">

                            Verify database connection,
                            storage permissions,
                            application key,
                            cache configuration,
                            queue configuration and more.

                        </p>

                        <a href="{{ route('health.check') }}"
                            class="btn btn-success mt-3 px-4">

                            Run Health Check

                        </a>

                    </div>

                    <div class="row mt-4">

                        <div class="col-lg-12">

                            <div class="card shadow">

                                <div class="card-body text-center">

                                    <div class="icon blue mb-3">

                                        <i class="fa-solid fa-cloud-arrow-up"></i>

                                    </div>

                                    <h3>Deployment Management</h3>

                                    <p class="text-muted">

                                        Manage deployments, search records, export CSV, update deployment status and delete records.

                                    </p>

                                    <a href="{{ route('deployments.index') }}"
                                        class="btn btn-dark btn-lg">

                                        <i class="fa-solid fa-list"></i>

                                        Open Deployment Manager

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="row mt-5">

            <div class="col-lg-12">

                <div class="card shadow info-card">

                    <div class="card-body">

                        <h3 class="mb-4">

                            <i class="fa-solid fa-circle-info text-primary"></i>

                            Project Information

                        </h3>

                        <div class="row">

                            <div class="col-md-6">

                                <table class="table">

                                    <tr>
                                        <th>Project</th>
                                        <td>Laravel Deployment Demo</td>
                                    </tr>

                                    <tr>
                                        <th>Framework</th>
                                        <td>Laravel 12</td>
                                    </tr>

                                    <tr>
                                        <th>PHP</th>
                                        <td>{{ PHP_VERSION }}</td>
                                    </tr>

                                    <tr>
                                        <th>Environment</th>
                                        <td>{{ config('app.env') }}</td>
                                    </tr>

                                </table>

                            </div>

                            <div class="col-md-6">

                                <table class="table">

                                    <tr>
                                        <th>Database</th>
                                        <td>{{ config('database.default') }}</td>
                                    </tr>

                                    <tr>
                                        <th>Cache Driver</th>
                                        <td>{{ config('cache.default') }}</td>
                                    </tr>

                                    <tr>
                                        <th>Queue Driver</th>
                                        <td>{{ config('queue.default') }}</td>
                                    </tr>

                                    <tr>
                                        <th>Server Time</th>
                                        <td>{{ now()->format('d M Y h:i A') }}</td>
                                    </tr>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="row mt-5">

            <div class="col-lg-12">

                <div class="card shadow">

                    <div class="card-header bg-dark text-white">

                        <h4>

                            <i class="fa-solid fa-clock-rotate-left"></i>

                            Latest Deployments

                        </h4>

                    </div>

                    <div class="card-body">

                        <table class="table table-hover">

                            <thead>

                                <tr>

                                    <th>ID</th>

                                    <th>Project</th>

                                    <th>Server</th>

                                    <th>Status</th>

                                    <th>Date</th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach(\App\Models\Deployment::latest()->take(5)->get() as $deployment)

                                <tr>

                                    <td>{{ $deployment->id }}</td>

                                    <td>{{ $deployment->project_name }}</td>

                                    <td>{{ $deployment->server_name }}</td>

                                    <td>

                                        @if($deployment->status=="Success")

                                        <span class="badge bg-success">

                                            Success

                                        </span>

                                        @else

                                        <span class="badge bg-danger">

                                            Failed

                                        </span>

                                        @endif

                                    </td>

                                    <td>

                                        {{ $deployment->deployment_date }}

                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <footer class="text-center mt-5">

            <hr>

            <p>
                Laravel 12 Deployment to Server & Continuous Integration Demo
            </p>

        </footer>

    </div>

</body>

</html>