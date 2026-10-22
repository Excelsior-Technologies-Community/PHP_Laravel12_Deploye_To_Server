<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Information</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .header {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: #fff;
            border-radius: 15px;
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .table td {
            vertical-align: middle;
        }

        .value {
            font-weight: 600;
            color: #0d6efd;
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
                        <i class="fa-solid fa-server"></i>
                        Server Information Dashboard
                    </h2>

                    <p class="mb-0">
                        Deployment environment details for your Laravel application.
                    </p>
                </div>

                <a href="{{ route('dashboard') }}" class="btn btn-light">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>

            </div>

        </div>

        <div class="card shadow">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fa-solid fa-circle-info"></i>
                    Server Details
                </h4>
            </div>

            <div class="card-body">

                <table class="table table-bordered table-hover">

                    <thead class="table-light">
                        <tr>
                            <th width="40%">Property</th>
                            <th>Value</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>Laravel Version</td>
                            <td class="value">{{ $serverInfo['laravel_version'] }}</td>
                        </tr>

                        <tr>
                            <td>PHP Version</td>
                            <td class="value">{{ $serverInfo['php_version'] }}</td>
                        </tr>

                        <tr>
                            <td>Application Environment</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ strtoupper($serverInfo['environment']) }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td>Debug Mode</td>
                            <td>
                                @if($serverInfo['app_debug'] == 'Enabled')
                                <span class="badge bg-warning text-dark">
                                    Enabled
                                </span>
                                @else
                                <span class="badge bg-success">
                                    Disabled
                                </span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>Application URL</td>
                            <td>{{ $serverInfo['app_url'] }}</td>
                        </tr>

                        <tr>
                            <td>Timezone</td>
                            <td>{{ $serverInfo['timezone'] }}</td>
                        </tr>

                        <tr>
                            <td>Database Connection</td>
                            <td>{{ $serverInfo['database'] }}</td>
                        </tr>

                        <tr>
                            <td>Cache Driver</td>
                            <td>{{ $serverInfo['cache_driver'] }}</td>
                        </tr>

                        <tr>
                            <td>Session Driver</td>
                            <td>{{ $serverInfo['session_driver'] }}</td>
                        </tr>

                        <tr>
                            <td>Queue Driver</td>
                            <td>{{ $serverInfo['queue_driver'] }}</td>
                        </tr>

                        <tr>
                            <td>Memory Limit</td>
                            <td>{{ $serverInfo['memory_limit'] }}</td>
                        </tr>

                        <tr>
                            <td>Max Execution Time</td>
                            <td>{{ $serverInfo['max_execution_time'] }}</td>
                        </tr>

                        <tr>
                            <td>Operating System</td>
                            <td>{{ $serverInfo['operating_system'] }}</td>
                        </tr>

                        <tr>
                            <td>Current Server Time</td>
                            <td>{{ $serverInfo['server_time'] }}</td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="text-center mt-4">

            <a href="{{ route('health.check') }}" class="btn btn-success btn-lg">
                <i class="fa-solid fa-heart-pulse"></i>
                Run Deployment Health Check
            </a>

        </div>

    </div>

</body>

</html>