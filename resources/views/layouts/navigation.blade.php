<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4 border-bottom border-secondary">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <span class="badge bg-primary px-2 py-1"><i class="fa-solid fa-server"></i> DEPLOY</span>
            <span>Laravel 12 DeployHub</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#deployNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="deployNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-house"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('deployment.simulator*') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('deployment.simulator') }}">
                        <i class="fa-solid fa-rocket text-warning"></i> Deployment Simulator
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('deployment.analytics*') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('deployment.analytics') }}">
                        <i class="fa-solid fa-chart-line text-info"></i> Analytics & Trends
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cicd.builder*') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('cicd.builder') }}">
                        <i class="fa-brands fa-github text-light"></i> CI/CD Workflow Builder
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('server.terminal*') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('server.terminal') }}">
                        <i class="fa-solid fa-terminal text-success"></i> SSH Terminal Lab
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('deployments.*') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('deployments.index') }}">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Deployments
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('server.info') }}" class="btn btn-sm btn-outline-info" title="View Server Architecture">
                    <i class="fa-solid fa-microchip"></i> Server Info
                </a>
                <a href="{{ route('health.check') }}" class="btn btn-sm btn-outline-success" title="Run Pre-deployment Diagnostics">
                    <i class="fa-solid fa-heart-pulse"></i> Health Check
                </a>
            </div>
        </div>
    </div>
</nav>
