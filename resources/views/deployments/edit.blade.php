<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Deployment #{{ $deployment->id }} - Laravel Deployment Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 14px; }
    </style>
</head>
<body>
    @include('layouts.navigation')

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card p-4 shadow">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0 text-light fw-bold"><i class="fa-solid fa-edit text-warning"></i> Edit Deployment #{{ $deployment->id }}</h4>
                        <a href="{{ route('deployments.index') }}" class="btn btn-sm btn-outline-secondary text-light">Back to List</a>
                    </div>

                    <form action="{{ route('deployments.update', $deployment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">PROJECT NAME</label>
                                <input type="text" name="project_name" class="form-control bg-dark text-light border-secondary" value="{{ $deployment->project_name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">SERVER NAME</label>
                                <input type="text" name="server_name" class="form-control bg-dark text-light border-secondary" value="{{ $deployment->server_name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">ENVIRONMENT</label>
                                <select name="environment" class="form-select bg-dark text-light border-secondary" required>
                                    <option value="Production" {{ $deployment->environment === 'Production' ? 'selected' : '' }}>Production</option>
                                    <option value="Staging" {{ $deployment->environment === 'Staging' ? 'selected' : '' }}>Staging</option>
                                    <option value="Development" {{ $deployment->environment === 'Development' ? 'selected' : '' }}>Development</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">STATUS</label>
                                <select name="status" class="form-select bg-dark text-light border-secondary" required>
                                    <option value="Success" {{ $deployment->status === 'Success' ? 'selected' : '' }}>Success</option>
                                    <option value="Failed" {{ $deployment->status === 'Failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">DEPLOYED BY</label>
                                <input type="text" name="deployed_by" class="form-control bg-dark text-light border-secondary" value="{{ $deployment->deployed_by }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">DEPLOYMENT TIMESTAMP</label>
                                <input type="datetime-local" name="deployment_date" class="form-control bg-dark text-light border-secondary" value="{{ $deployment->deployment_date ? $deployment->deployment_date->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold">REMARKS / RELEASE NOTES</label>
                                <textarea name="remarks" rows="3" class="form-control bg-dark text-light border-secondary">{{ $deployment->remarks }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-warning px-4"><i class="fa-solid fa-check me-1"></i> Update Deployment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
