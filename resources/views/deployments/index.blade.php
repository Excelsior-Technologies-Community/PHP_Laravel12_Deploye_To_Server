<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deployment Manager</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
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

        .stat-card {
            text-align: center;
            padding: 20px;
        }

        .stat-card i {
            font-size: 35px;
            margin-bottom: 10px;
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
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        Deployment Manager
                    </h2>

                </div>

                <div>

                    <a href="{{ route('dashboard') }}" class="btn btn-light">

                        <i class="fa-solid fa-house"></i>

                        Dashboard

                    </a>

                </div>

            </div>

        </div>

        @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

        @endif


        <div class="row mb-4">

            <div class="col-md-3">

                <div class="card shadow stat-card">

                    <i class="fa-solid fa-layer-group text-primary"></i>

                    <h2>{{ $statistics['total'] }}</h2>

                    <strong>Total</strong>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow stat-card">

                    <i class="fa-solid fa-circle-check text-success"></i>

                    <h2>{{ $statistics['success'] }}</h2>

                    <strong>Successful</strong>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow stat-card">

                    <i class="fa-solid fa-circle-xmark text-danger"></i>

                    <h2>{{ $statistics['failed'] }}</h2>

                    <strong>Failed</strong>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow stat-card">

                    <i class="fa-solid fa-calendar-day text-warning"></i>

                    <h2>{{ $statistics['today'] }}</h2>

                    <strong>Today</strong>

                </div>

            </div>

        </div>


        <div class="card shadow">

            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-8">

                        <form method="GET" action="{{ route('deployments.index') }}">

                            <div class="input-group">

                                <input
                                    type="text"
                                    class="form-control"
                                    name="search"
                                    placeholder="Search Project / Server / Status..."
                                    value="{{ request('search') }}">

                                <button class="btn btn-primary">

                                    <i class="fa fa-search"></i>

                                    Search

                                </button>

                            </div>

                        </form>

                    </div>

                    <div class="col-md-4 text-end">

                        <a href="{{ route('deployments.export') }}"
                            class="btn btn-success">

                            <i class="fa-solid fa-file-csv"></i>

                            Export CSV

                        </a>

                    </div>

                </div>

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>

                            <th>Project</th>

                            <th>Server</th>

                            <th>Environment</th>

                            <th>Engineer</th>

                            <th>Status</th>

                            <th>Date</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($deployments as $deployment)

                        <tr>

                            <td>{{ $deployment->id }}</td>

                            <td>{{ $deployment->project_name }}</td>

                            <td>{{ $deployment->server_name }}</td>

                            <td>{{ $deployment->environment }}</td>



                            <td>{{ $deployment->deployed_by }}</td>

                            <td>

                                @if($deployment->status=='Success')

                                <span class="badge bg-success">

                                    Success

                                </span>

                                @else

                                <span class="badge bg-danger">

                                    Failed

                                </span>

                                @endif

                            </td>

                            <td>{{ $deployment->deployment_date }}</td>

                            <td>

                                <form
                                    action="{{ route('deployments.delete',$deployment->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this deployment?')">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        class="btn btn-danger btn-sm">

                                        <i class="fa fa-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="8" class="text-center">

                                No Deployment Found

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

                <div class="mt-3">

                    {{ $deployments->links() }}

                </div>

            </div>

        </div>

    </div>

</body>

</html>