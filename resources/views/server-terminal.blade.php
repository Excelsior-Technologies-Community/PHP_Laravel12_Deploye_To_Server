<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server SSH Terminal Simulator & Reference Lab - Laravel 12</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .terminal-container {
            background: #080c14;
            border: 1px solid #334155;
            border-radius: 12px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.7);
            overflow: hidden;
        }
        .terminal-header {
            background: #1e293b;
            padding: 10px 18px;
            border-bottom: 1px solid #334155;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .term-dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; }
        .terminal-body {
            padding: 20px;
            min-height: 400px;
            max-height: 480px;
            overflow-y: auto;
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 13.5px;
            color: #e2e8f0;
            line-height: 1.5;
        }
        .prompt-line {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }
        .prompt-label {
            color: #22c55e;
            font-weight: bold;
            white-space: nowrap;
        }
        .terminal-input {
            background: transparent;
            border: none;
            outline: none;
            color: #38bdf8;
            font-family: inherit;
            font-size: inherit;
            flex-grow: 1;
        }
        .quick-pill {
            background: #1e293b;
            border: 1px solid #334155;
            color: #38bdf8;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: monospace;
        }
        .quick-pill:hover {
            background: #38bdf8;
            color: #0f172a;
            border-color: #38bdf8;
        }
        .ref-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 16px;
            transition: all 0.2s ease;
        }
        .ref-card:hover {
            border-color: #38bdf8;
        }
    </style>
</head>
<body>
    @include('layouts.navigation')

    <div class="container py-2">
        <!-- Title -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold text-light mb-1">
                    <i class="fa-solid fa-terminal text-success"></i> Server SSH Terminal Simulator & Command Reference Lab
                </h3>
                <p class="text-secondary mb-0">Interactive browser shell emulator to practice Linux system operations and deployment workflows safely</p>
            </div>
            <button class="btn btn-sm btn-outline-danger" onclick="clearTerminal()">
                <i class="fa-solid fa-trash me-1"></i> Clear Screen
            </button>
        </div>

        <!-- Quick Command Launcher Pills -->
        <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted small fw-bold"><i class="fa-solid fa-bolt text-warning"></i> Quick Commands:</span>
            <span class="quick-pill" onclick="runCommandDirect('ssh root@192.168.1.100')">ssh root@192.168.1.100</span>
            <span class="quick-pill" onclick="runCommandDirect('git pull origin main')">git pull origin main</span>
            <span class="quick-pill" onclick="runCommandDirect('composer install --no-dev')">composer install</span>
            <span class="quick-pill" onclick="runCommandDirect('php artisan migrate')">php artisan migrate</span>
            <span class="quick-pill" onclick="runCommandDirect('php artisan optimize')">php artisan optimize</span>
            <span class="quick-pill" onclick="runCommandDirect('sudo systemctl reload nginx')">reload nginx</span>
            <span class="quick-pill" onclick="runCommandDirect('chmod -R 775 storage')">chmod 775 storage</span>
            <span class="quick-pill" onclick="runCommandDirect('df -h')">df -h</span>
            <span class="quick-pill" onclick="runCommandDirect('free -m')">free -m</span>
        </div>

        <!-- Terminal Window -->
        <div class="terminal-container shadow mb-4">
            <div class="terminal-header">
                <span class="term-dot bg-danger"></span>
                <span class="term-dot bg-warning"></span>
                <span class="term-dot bg-success"></span>
                <span class="text-muted small ms-2"><i class="fa-solid fa-server"></i> root@ubuntu-vps-production: /var/www/PHP_Laravel12_Deploye_To_Server (bash)</span>
            </div>
            <div id="terminalBody" class="terminal-body">
                <div class="text-secondary mb-2">Welcome to Ubuntu 24.04 LTS (GNU/Linux 6.8.0-31-generic x86_64)</div>
                <div class="text-secondary mb-2">* Documentation:  https://help.ubuntu.com</div>
                <div class="text-secondary mb-3">* System load:    0.18               Memory usage: 36% of 4.00GB</div>
                <div class="text-info mb-3">Type <span class="text-warning">help</span> to list available simulated commands or click any of the quick command buttons above.</div>
                <div id="commandHistory"></div>

                <div class="prompt-line">
                    <span class="prompt-label">root@ubuntu-vps:/var/www/app#</span>
                    <input type="text" id="terminalInput" class="terminal-input" autocomplete="off" spellcheck="false" autofocus placeholder="Type a command (e.g. git pull, php artisan migrate, help)...">
                </div>
            </div>
        </div>

        <!-- Command Reference Lab -->
        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-light fw-bold mb-0">
                    <i class="fa-solid fa-book-bookmark text-primary"></i> Production Deployment Command Reference Lab
                </h5>
                <input type="text" id="refSearch" class="form-control form-control-sm bg-dark text-light border-secondary" style="width: 250px;" placeholder="Search commands...">
            </div>

            <div class="row g-3" id="referenceGrid">
                @foreach($commandReference as $ref)
                    <div class="col-md-6 ref-item" data-text="{{ strtolower($ref['command'] . ' ' . $ref['category'] . ' ' . $ref['description']) }}">
                        <div class="ref-card h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary" style="font-size: 10px;">{{ $ref['category'] }}</span>
                                <button class="btn btn-sm btn-outline-info py-0 px-2" style="font-size: 11px;" onclick="runCommandDirect('{{ $ref['example'] }}')">
                                    <i class="fa-solid fa-play"></i> Run in Terminal
                                </button>
                            </div>
                            <code class="text-warning fw-bold d-block mb-1">{{ $ref['command'] }}</code>
                            <p class="text-light opacity-80 small mb-2">{{ $ref['description'] }}</p>
                            <div class="text-muted" style="font-size: 11px;">Example: <code>{{ $ref['example'] }}</code></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const input = document.getElementById('terminalInput');
        const historyContainer = document.getElementById('commandHistory');
        const terminalBody = document.getElementById('terminalBody');

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const cmd = this.value.trim();
                this.value = '';
                if (cmd) {
                    executeTerminalCommand(cmd);
                }
            }
        });

        async function executeTerminalCommand(cmd) {
            // Render user command line
            const userLine = document.createElement('div');
            userLine.className = 'prompt-line mb-1';
            userLine.innerHTML = `<span class="prompt-label">root@ubuntu-vps:/var/www/app#</span> <span class="text-light">${escapeHtml(cmd)}</span>`;
            historyContainer.appendChild(userLine);

            try {
                const res = await fetch("{{ route('server.terminal.execute') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ command: cmd })
                });

                const data = await res.json();

                if (data.output === '__CLEAR__') {
                    historyContainer.innerHTML = '';
                    return;
                }

                if (data.output) {
                    const out = document.createElement('div');
                    out.className = data.exit_code === 0 ? 'text-info mb-2' : 'text-danger mb-2';
                    out.style.whiteSpace = 'pre-wrap';
                    out.textContent = data.output;
                    historyContainer.appendChild(out);
                }

            } catch (err) {
                const errDiv = document.createElement('div');
                errDiv.className = 'text-danger mb-2';
                errDiv.textContent = `Execution failed: ${err.message}`;
                historyContainer.appendChild(errDiv);
            }

            terminalBody.scrollTop = terminalBody.scrollHeight;
        }

        function runCommandDirect(cmd) {
            executeTerminalCommand(cmd);
        }

        function clearTerminal() {
            historyContainer.innerHTML = '';
            input.focus();
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Search reference cards
        document.getElementById('refSearch').addEventListener('input', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.ref-item').forEach(el => {
                const text = el.getAttribute('data-text');
                el.style.display = text.includes(term) ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>
