<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Actions CI/CD Workflow Builder - Laravel 12</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .config-card { background: #1e293b; border: 1px solid #334155; border-radius: 14px; padding: 22px; }
        .step-item {
            background: #0b1120;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }
        .step-item:hover { border-color: #38bdf8; }
        .yaml-box {
            background: #060913;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 18px;
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 13px;
            color: #38bdf8;
            max-height: 720px;
            overflow-y: auto;
            white-space: pre-wrap;
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
                    <i class="fa-brands fa-github text-light"></i> GitHub Actions CI/CD Workflow Builder & YAML Studio
                </h3>
                <p class="text-secondary mb-0">Design, customize, and generate production-ready automated continuous delivery pipelines for Laravel 12</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-info" onclick="copyYaml()"><i class="fa-solid fa-copy me-1"></i> Copy YAML</button>
                <button class="btn btn-primary" onclick="downloadYaml()"><i class="fa-solid fa-download me-1"></i> Download ci.yml</button>
                <button class="btn btn-success" onclick="saveToRepo()"><i class="fa-solid fa-cloud-arrow-up me-1"></i> Save to Project (.github/)</button>
            </div>
        </div>

        <div id="statusToast" class="alert alert-success border-success shadow py-2 px-3 small" style="display:none;"></div>

        <div class="row g-4">
            <!-- Left: Configurator Steps -->
            <div class="col-lg-6">
                <div class="config-card shadow">
                    <h5 class="text-light fw-bold mb-3"><i class="fa-solid fa-sliders text-primary"></i> Pipeline Specifications</h5>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">WORKFLOW NAME</label>
                        <input type="text" id="workflowName" class="form-control bg-dark text-light border-secondary" value="Laravel 12 Production CI/CD Pipeline" oninput="updateYamlPreview()">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">PHP RUNTIME VERSION</label>
                            <select id="phpVersion" class="form-select bg-dark text-light border-secondary" onchange="updateYamlPreview()">
                                <option value="8.2" selected>PHP 8.2 (LTS Recommended)</option>
                                <option value="8.3">PHP 8.3 (Modern)</option>
                                <option value="8.4">PHP 8.4 (Latest)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">GITHUB RUNNER</label>
                            <input type="text" class="form-control bg-dark text-muted border-secondary" value="ubuntu-latest" disabled>
                        </div>
                    </div>

                    <h6 class="text-info fw-bold mt-4 mb-2"><i class="fa-solid fa-bolt"></i> Event Triggers</h6>
                    <div class="step-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-light small"><i class="fa-solid fa-code-commit text-primary me-1"></i> Push to 'main' branch</div>
                            <span class="text-muted" style="font-size: 11px;">Automatically runs CI and deploys to production</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="triggerPushMain" checked onchange="updateYamlPreview()">
                        </div>
                    </div>

                    <div class="step-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-light small"><i class="fa-solid fa-code-branch text-warning me-1"></i> Push to 'staging' branch</div>
                            <span class="text-muted" style="font-size: 11px;">Triggers build and test for pre-release validation</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="triggerPushStaging" checked onchange="updateYamlPreview()">
                        </div>
                    </div>

                    <div class="step-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-light small"><i class="fa-solid fa-code-pull-request text-success me-1"></i> Pull Request to 'main'</div>
                            <span class="text-muted" style="font-size: 11px;">Runs tests on PRs to prevent broken code from merging</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="triggerPr" checked onchange="updateYamlPreview()">
                        </div>
                    </div>

                    <h6 class="text-info fw-bold mt-4 mb-2"><i class="fa-solid fa-list-check"></i> Pipeline Execution Steps</h6>

                    <div class="step-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-light small"><i class="fa-solid fa-box-open text-primary me-1"></i> Composer Package Install & Caching</div>
                            <span class="text-muted" style="font-size: 11px;">Caches vendor directory and installs production dependencies</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="stepComposer" checked onchange="updateYamlPreview()">
                        </div>
                    </div>

                    <div class="step-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-light small"><i class="fa-solid fa-key text-warning me-1"></i> Environment & APP_KEY Setup</div>
                            <span class="text-muted" style="font-size: 11px;">Copies .env.example to .env and generates encryption key</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="stepEnv" checked onchange="updateYamlPreview()">
                        </div>
                    </div>

                    <div class="step-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-light small"><i class="fa-brands fa-npm text-danger me-1"></i> Node.js & Vite Asset Compilation</div>
                            <span class="text-muted" style="font-size: 11px;">Runs npm ci and npm run build for production assets</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="stepNpm" checked onchange="updateYamlPreview()">
                        </div>
                    </div>

                    <div class="step-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-light small"><i class="fa-solid fa-vial text-info me-1"></i> Automated PHPUnit / Pest Test Suite</div>
                            <span class="text-muted" style="font-size: 11px;">php artisan test --parallel with zero regression tolerance</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="stepTests" checked onchange="updateYamlPreview()">
                        </div>
                    </div>

                    <div class="step-item d-flex justify-content-between align-items-center border-warning">
                        <div>
                            <div class="fw-bold text-warning small"><i class="fa-solid fa-terminal text-warning me-1"></i> Deploy to VPS via SSH (Appleboy SSH Action)</div>
                            <span class="text-muted" style="font-size: 11px;">Connects to server secrets and runs git pull, migrate, and nginx reload</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="stepDeploySsh" checked onchange="updateYamlPreview()">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Real-time Live YAML Output -->
            <div class="col-lg-6">
                <div class="config-card shadow h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="text-light fw-bold mb-0"><i class="fa-solid fa-file-code text-warning"></i> Generated GitHub Actions YAML (ci.yml)</h5>
                        <span class="badge bg-dark border border-secondary text-info">YAML Spec v1.2</span>
                    </div>
                    <div id="yamlPreviewBox" class="yaml-box">Loading YAML template...</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentYaml = '';

        function getConfigPayload() {
            return {
                workflow_name: document.getElementById('workflowName').value,
                php_version: document.getElementById('phpVersion').value,
                trigger_push_main: document.getElementById('triggerPushMain').checked ? '1' : '0',
                trigger_push_staging: document.getElementById('triggerPushStaging').checked ? '1' : '0',
                trigger_pr: document.getElementById('triggerPr').checked ? '1' : '0',
                step_composer: document.getElementById('stepComposer').checked ? '1' : '0',
                step_env: document.getElementById('stepEnv').checked ? '1' : '0',
                step_npm: document.getElementById('stepNpm').checked ? '1' : '0',
                step_tests: document.getElementById('stepTests').checked ? '1' : '0',
                step_deploy_ssh: document.getElementById('stepDeploySsh').checked ? '1' : '0',
                _token: '{{ csrf_token() }}'
            };
        }

        async function updateYamlPreview() {
            try {
                const res = await fetch("{{ route('cicd.builder.generate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(getConfigPayload())
                });
                const data = await res.json();
                currentYaml = data.yaml;
                document.getElementById('yamlPreviewBox').textContent = currentYaml;
            } catch (e) {
                console.error(e);
            }
        }

        function copyYaml() {
            navigator.clipboard.writeText(currentYaml);
            showToast('YAML copied to clipboard successfully!');
        }

        function downloadYaml() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('cicd.builder.download') }}";

            const payload = getConfigPayload();
            for (const key in payload) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = payload[key];
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        async function saveToRepo() {
            try {
                const res = await fetch("{{ route('cicd.builder.save') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(getConfigPayload())
                });
                const data = await res.json();
                showToast(data.message);
            } catch (e) {
                showToast('Failed to save YAML: ' + e.message, true);
            }
        }

        function showToast(msg, isError = false) {
            const t = document.getElementById('statusToast');
            t.className = isError ? 'alert alert-danger shadow py-2 px-3 small' : 'alert alert-success shadow py-2 px-3 small';
            t.textContent = msg;
            t.style.display = 'block';
            setTimeout(() => { t.style.display = 'none'; }, 4000);
        }

        document.addEventListener('DOMContentLoaded', updateYamlPreview);
    </script>
</body>
</html>
