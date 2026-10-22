<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deployment Health Check</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .header {
            background: linear-gradient(135deg, #198754, #20c997);
            color: #fff;
            border-radius: 15px;
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .status-icon {
            font-size: 22px;
        }

        .summary-card {
            border-left: 5px solid #198754;
        }

        .table td {
            vertical-align: middle;
        }
    </style>

</head>

<body>
    @include('layouts.navigation')

    <div class="container py-5">

        <div class="header p-4 shadow mb-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h2>
                        <i class="fa-solid fa-heart-pulse"></i>
                        Deployment Health Check
                    </h2>

                    <p class="mb-0">
                        Verify whether your Laravel application is ready for deployment.
                    </p>
                </div>

                <a href="{{ route('dashboard') }}" class="btn btn-light">
                    <i class="fa-solid fa-house"></i>
                    Dashboard
                </a>

            </div>

        </div>

        @php
        $passed = collect($checks)->filter()->count();
        $failed = count($checks) - $passed;
        @endphp

        <div class="row mb-4">

            <div class="col-md-4">

                <div class="card shadow summary-card">

                    <div class="card-body text-center">

                        <h5>Total Checks</h5>

                        <h2 class="text-primary">
                            {{ count($checks) }}
                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card shadow">

                    <div class="card-body text-center">

                        <h5>Passed</h5>

                        <h2 class="text-success">
                            {{ $passed }}
                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card shadow">

                    <div class="card-body text-center">

                        <h5>Failed</h5>

                        <h2 class="text-danger">
                            {{ $failed }}
                        </h2>

                    </div>

                </div>

            </div>

        </div>

        <div class="card shadow">

            <div class="card-header bg-success text-white">

                <h4 class="mb-0">
                    <i class="fa-solid fa-list-check"></i>
                    Deployment Checklist
                </h4>

            </div>

            <div class="card-body">

                <table class="table table-hover table-bordered">

                    <thead class="table-light">

                        <tr>

                            <th width="70%">
                                Check
                            </th>

                            <th width="30%">
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($checks as $name => $status)

                        <tr>

                            <td>

                                {{ $name }}

                            </td>

                            <td>

                                @if($status)

                                <span class="badge bg-success fs-6">

                                    <i class="fa-solid fa-circle-check"></i>

                                    Passed

                                </span>

                                @else

                                <span class="badge bg-danger fs-6">

                                    <i class="fa-solid fa-circle-xmark"></i>

                                    Failed

                                </span>

                                @endif

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card shadow mt-4">

            <div class="card-body">

                <h4 class="mb-3">

                    <i class="fa-solid fa-lightbulb text-warning"></i>

                    Deployment Recommendations

                </h4>

                <ul class="mb-0">

                    <li>Ensure your <strong>.env</strong> file is configured correctly.</li>

                    <li>Generate the application key before deployment.</li>

                    <li>Set proper permissions on <strong>storage</strong> and <strong>bootstrap/cache</strong>.</li>

                    <li>Run <code>php artisan migrate --force</code> on the production server.</li>

                    <li>Cache configuration using <code>php artisan config:cache</code>.</li>

                    <li>Cache routes using <code>php artisan route:cache</code>.</li>

                    <li>Optimize the application using <code>php artisan optimize</code>.</li>

                    <li>Restart queue workers after deployment if queues are used.</li>

                </ul>

            </div>

        </div>

        <div class="text-center mt-4">

            <a href="{{ route('server.info') }}" class="btn btn-primary btn-lg">

                <i class="fa-solid fa-server"></i>

                View Server Information

            </a>

        </div>

    </div>

</body>

</html>