<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One-Click Deployment Simulator & Live Pipeline - Laravel 12</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .config-card { background: #1e293b; border: 1px solid #334155; border-radius: 14px; padding: 22px; }
        .pipeline-container {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .pipeline-track {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            margin: 20px 0;
        }
        .pipeline-track::before {
            content: '';
            position: absolute;
            top: 32px;
            left: 40px;
            right: 40px;
            height: 4px;
            background: #334155;
            z-index: 1;
        }
        .pipeline-stage {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 15%;
        }
        .stage-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #0b1120;
            border: 3px solid #475569;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin: 0 auto 10px auto;
            transition: all 0.3s ease;
        }
        .stage-active .stage-icon {
            border-color: #38bdf8;
            color: #38bdf8;
            background: rgba(56, 189, 248, 0.15);
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.5);
            animation: pulse 1.5s infinite;
        }
        .stage-success .stage-icon {
            border-color: #22c55e;
            color: #22c55e;
            background: rgba(34, 197, 94, 0.15);
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.3);
        }
        .stage-failed .stage-icon {
            border-color: #ef4444;
            color: #ef4444;
            background: rgba(239, 68, 68, 0.15);
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.08); }
            100% { transform: scale(1); }
        }
        .terminal-box {
            background: #0b1120;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 16px;
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 13px;
            min-height: 280px;
            max-height: 380px;
            overflow-y: auto;
            color: #38bdf8;
        }
        .term-header {
            background: #1e293b;
            border-bottom: 1px solid #334155;
            padding: 8px 16px;
            border-radius: 12px 12px 0 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .term-dot { width: 11px; height: 11px; border-radius: 50%; display: inline-block; }
    </style>
</head>
<body>
    @include('layouts.navigation')

    <div class="container py-2">
        <!-- Title & Subtitle -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-light mb-1">
                    <i class="fa-solid fa-rocket text-warning"></i> One-Click Deployment Simulator & Live Pipeline
                </h3>
                <p class="text-secondary mb-0">Visualize multi-stage continuous delivery workflows in real-time with timing metrics and live stdout telemetry</p>
            </div>
            <a href="{{ route('deployments.index') }}" class="btn btn-outline-secondary text-light">
                <i class="fa-solid fa-list"></i> Deployment Log History
            </a>
        </div>

        <!-- Configuration Panel -->
        <div class="config-card mb-4 shadow">
            <h6 class="text-info fw-bold mb-3"><i class="fa-solid fa-sliders"></i> Deployment Target & Simulation Parameters</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">TARGET REPOSITORY</label>
                    <select id="projectSelect" class="form-select bg-dark text-light border-secondary">
                        @foreach($projects as $p)
                            <option value="{{ $p }}">{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">DESTINATION SERVER</label>
                    <select id="serverSelect" class="form-select bg-dark text-light border-secondary">
                        @foreach($defaultServers as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-bold">ENVIRONMENT</label>
                    <select id="envSelect" class="form-select bg-dark text-light border-secondary">
                        <option value="Production">Production</option>
                        <option value="Staging">Staging</option>
                        <option value="Development">Development</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-bold">OPERATOR / BOT</label>
                    <input type="text" id="deployerInput" class="form-control bg-dark text-light border-secondary" value="DevOps Lead">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-bold">TEST FAULT INJECTION</label>
                    <select id="failSelect" class="form-select bg-dark text-warning border-secondary">
                        <option value="none">None (100% Success)</option>
                        <option value="git">Fail at Git Fetch</option>
                        <option value="composer">Fail at Composer</option>
                        <option value="npm">Fail at NPM Build</option>
                        <option value="migrate">Fail at DB Migrate</option>
                        <option value="cache">Fail at Cache Warm</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top border-secondary">
                <div class="text-secondary small">
                    <i class="fa-solid fa-info-circle text-info"></i> Pipeline execution will record an entry directly into the <code>deployments</code> table upon completion.
                </div>
                <button id="btnDeployNow" class="btn btn-warning btn-lg fw-bold px-4" onclick="triggerDeployment()">
                    <i class="fa-solid fa-play-circle me-1"></i> Trigger Deployment Now
                </button>
            </div>
        </div>

        <!-- 6-Stage Visual Pipeline -->
        <div class="pipeline-container shadow">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0 text-light fw-bold"><i class="fa-solid fa-diagram-project text-primary"></i> 6-Stage Continuous Delivery Pipeline</h5>
                <span id="pipelineStatusBadge" class="badge bg-secondary px-3 py-2">STATUS: STANDBY</span>
            </div>

            <div class="pipeline-track">
                <!-- Stage 1 -->
                <div class="pipeline-stage" id="stage-git">
                    <div class="stage-icon"><i class="fa-brands fa-git-alt"></i></div>
                    <div class="fw-bold small text-light">1. Git Pull</div>
                    <div class="text-muted" style="font-size: 11px;" id="timer-git">Pending</div>
                </div>

                <!-- Stage 2 -->
                <div class="pipeline-stage" id="stage-composer">
                    <div class="stage-icon"><i class="fa-solid fa-box"></i></div>
                    <div class="fw-bold small text-light">2. Composer</div>
                    <div class="text-muted" style="font-size: 11px;" id="timer-composer">Pending</div>
                </div>

                <!-- Stage 3 -->
                <div class="pipeline-stage" id="stage-npm">
                    <div class="stage-icon"><i class="fa-brands fa-npm"></i></div>
                    <div class="fw-bold small text-light">3. Vite Asset Build</div>
                    <div class="text-muted" style="font-size: 11px;" id="timer-npm">Pending</div>
                </div>

                <!-- Stage 4 -->
                <div class="pipeline-stage" id="stage-migrate">
                    <div class="stage-icon"><i class="fa-solid fa-database"></i></div>
                    <div class="fw-bold small text-light">4. DB Migrate</div>
                    <div class="text-muted" style="font-size: 11px;" id="timer-migrate">Pending</div>
                </div>

                <!-- Stage 5 -->
                <div class="pipeline-stage" id="stage-cache">
                    <div class="stage-icon"><i class="fa-solid fa-bolt"></i></div>
                    <div class="fw-bold small text-light">5. Cache Warm</div>
                    <div class="text-muted" style="font-size: 11px;" id="timer-cache">Pending</div>
                </div>

                <!-- Stage 6 -->
                <div class="pipeline-stage" id="stage-live">
                    <div class="stage-icon"><i class="fa-solid fa-circle-check"></i></div>
                    <div class="fw-bold small text-light">6. Go Live</div>
                    <div class="text-muted" style="font-size: 11px;" id="timer-live">Pending</div>
                </div>
            </div>

            <!-- Overall Progress Bar -->
            <div class="progress mt-3" style="height: 8px;">
                <div id="pipelineProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 0%;"></div>
            </div>
        </div>

        <!-- Terminal Output Window -->
        <div class="card bg-dark border-secondary shadow mb-4">
            <div class="term-header">
                <span class="term-dot bg-danger"></span>
                <span class="term-dot bg-warning"></span>
                <span class="term-dot bg-success"></span>
                <span class="text-muted small ms-2"><i class="fa-solid fa-terminal"></i> Live Pipeline Telemetry Console (STDOUT / STDERR)</span>
                <span class="ms-auto small text-secondary" id="termExecutionClock">--:--:--</span>
            </div>
            <div class="card-body p-0">
                <div id="terminalOutput" class="terminal-box">
                    <span class="text-secondary">// Pipeline simulator ready. Click "Trigger Deployment Now" above to start.</span><br>
                    <span class="text-secondary">// Stages: Git Pull ➔ Composer ➔ Vite Build ➔ Artisan Migrate ➔ Cache Warm ➔ Go Live</span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const stageIds = ['git', 'composer', 'npm', 'migrate', 'cache', 'live'];

        function resetPipelineUi() {
            stageIds.forEach(id => {
                const el = document.getElementById(`stage-${id}`);
                el.className = 'pipeline-stage';
                document.getElementById(`timer-${id}`).textContent = 'Pending';
            });
            document.getElementById('pipelineProgressBar').style.width = '0%';
            document.getElementById('pipelineProgressBar').className = 'progress-bar progress-bar-striped progress-bar-animated bg-primary';
            document.getElementById('pipelineStatusBadge').className = 'badge bg-warning text-dark px-3 py-2';
            document.getElementById('pipelineStatusBadge').textContent = 'STATUS: IN PROGRESS';
            document.getElementById('terminalOutput').innerHTML = '';
        }

        function appendLog(text, color = 'text-info') {
            const box = document.getElementById('terminalOutput');
            const p = document.createElement('div');
            p.className = color;
            p.textContent = text;
            box.appendChild(p);
            box.scrollTop = box.scrollHeight;
        }

        async function triggerDeployment() {
            const btn = document.getElementById('btnDeployNow');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Running Pipeline...';

            resetPipelineUi();

            const payload = {
                project_name: document.getElementById('projectSelect').value,
                server_name: document.getElementById('serverSelect').value,
                environment: document.getElementById('envSelect').value,
                deployed_by: document.getElementById('deployerInput').value,
                fail_stage: document.getElementById('failSelect').value,
                _token: '{{ csrf_token() }}'
            };

            appendLog(`[${new Date().toLocaleTimeString()}] Initializing deployment pipeline for ${payload.project_name} to ${payload.server_name}...`, 'text-light');

            try {
                const response = await fetch("{{ route('deployment.simulator.execute') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                // Animate stages sequentially with visual timing
                let stepPercent = 100 / stageIds.length;
                let currentPercent = 0;

                for (let i = 0; i < data.stages.length; i++) {
                    const stage = data.stages[i];
                    const stageEl = document.getElementById(`stage-${stage.id}`);

                    stageEl.className = 'pipeline-stage stage-active';
                    document.getElementById(`timer-${stage.id}`).textContent = 'Executing...';
                    appendLog(`\n>>> [Stage ${i+1}/6]: ${stage.name}`, 'text-warning');
                    appendLog(`$ ${stage.command}`, 'text-secondary');

                    // Simulated realistic delay for animation
                    await new Promise(r => setTimeout(r, 650));

                    // Print logs
                    stage.logs.forEach(l => {
                        appendLog(`  ${l}`, stage.status === 'failed' ? 'text-danger' : 'text-info');
                    });

                    if (stage.status === 'success') {
                        stageEl.className = 'pipeline-stage stage-success';
                        document.getElementById(`timer-${stage.id}`).textContent = `✓ ${stage.duration}s`;
                        currentPercent += stepPercent;
                        document.getElementById('pipelineProgressBar').style.width = `${Math.min(100, Math.round(currentPercent))}%`;
                    } else {
                        stageEl.className = 'pipeline-stage stage-failed';
                        document.getElementById(`timer-${stage.id}`).textContent = `✗ Failed (${stage.duration}s)`;
                        break;
                    }
                }

                // Final status update
                if (data.overall_status === 'Success') {
                    document.getElementById('pipelineProgressBar').className = 'progress-bar bg-success';
                    document.getElementById('pipelineProgressBar').style.width = '100%';
                    document.getElementById('pipelineStatusBadge').className = 'badge bg-success px-3 py-2';
                    document.getElementById('pipelineStatusBadge').textContent = 'STATUS: DEPLOYED SUCCESS';
                    appendLog(`\n✓ SUCCESS: ${data.message} (Recorded as Deployment #${data.deployment_id})`, 'text-success fw-bold');
                } else {
                    document.getElementById('pipelineProgressBar').className = 'progress-bar bg-danger';
                    document.getElementById('pipelineStatusBadge').className = 'badge bg-danger px-3 py-2';
                    document.getElementById('pipelineStatusBadge').textContent = 'STATUS: FAILED';
                    appendLog(`\n✗ FAILED: ${data.message} (Recorded as Deployment #${data.deployment_id})`, 'text-danger fw-bold');
                }

            } catch (err) {
                appendLog(`Fatal simulation error: ${err.message}`, 'text-danger');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-play-circle me-1"></i> Trigger Deployment Now';
            }
        }
    </script>
</body>
</html>
