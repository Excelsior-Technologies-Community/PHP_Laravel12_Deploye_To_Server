<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TerminalLabController extends Controller
{
    /**
     * Display the Browser SSH Terminal Simulator & Command Reference Lab
     */
    public function index()
    {
        $commandReference = [
            [
                'command' => 'ssh root@your_server_ip',
                'category' => 'Connection',
                'description' => 'Securely connects to your remote Ubuntu VPS via encrypted SSH protocol.',
                'example' => 'ssh -p 22 ubuntu@192.168.1.50',
            ],
            [
                'command' => 'git pull origin main',
                'category' => 'Version Control',
                'description' => 'Fetches and merges the latest committed source code from GitHub main branch into the server directory.',
                'example' => 'git pull origin main',
            ],
            [
                'command' => 'composer install --no-dev --optimize-autoloader',
                'category' => 'Dependencies',
                'description' => 'Installs strictly production PHP packages without development tools like PHPUnit/Faker, and creates a high-performance classmap.',
                'example' => 'composer install --no-dev -o',
            ],
            [
                'command' => 'php artisan migrate --force',
                'category' => 'Database',
                'description' => 'Runs all pending database schema migrations on a production environment. The --force flag bypasses production confirmation prompts.',
                'example' => 'php artisan migrate --force',
            ],
            [
                'command' => 'php artisan optimize',
                'category' => 'Performance',
                'description' => 'Caches configuration, routes, events, and compiled Blade views in memory for sub-millisecond response speeds.',
                'example' => 'php artisan optimize',
            ],
            [
                'command' => 'sudo chmod -R 775 storage bootstrap/cache',
                'category' => 'Permissions',
                'description' => 'Grants write permissions to the storage and bootstrap/cache directories so Nginx/PHP-FPM can create sessions, logs, and caches.',
                'example' => 'sudo chmod -R 775 storage bootstrap/cache',
            ],
            [
                'command' => 'sudo systemctl reload nginx',
                'category' => 'Web Server',
                'description' => 'Gracefully reloads Nginx configuration without dropping active HTTP connections.',
                'example' => 'sudo nginx -t && sudo systemctl reload nginx',
            ],
            [
                'command' => 'sudo systemctl restart php8.2-fpm',
                'category' => 'PHP Runtime',
                'description' => 'Restarts the FastCGI Process Manager to clear PHP OPcache and apply new environment updates.',
                'example' => 'sudo systemctl restart php8.2-fpm',
            ],
        ];

        return view('server-terminal', compact('commandReference'));
    }

    /**
     * Interpret and Execute Simulated Commands
     */
    public function execute(Request $request)
    {
        $rawCommand = trim($request->input('command', ''));
        $cmd = strtolower($rawCommand);

        if (empty($cmd)) {
            return response()->json(['output' => '', 'exit_code' => 0]);
        }

        $output = '';
        $exitCode = 0;

        if ($cmd === 'clear') {
            return response()->json(['output' => '__CLEAR__', 'exit_code' => 0]);
        }

        if ($cmd === 'help' || $cmd === 'commands') {
            $output = "Available Simulated Commands:\n"
                . "  ssh [user@ip]              - Simulate remote VPS connection\n"
                . "  git pull origin main       - Fetch latest commits from GitHub\n"
                . "  git status                 - Check working tree status\n"
                . "  git log                    - View recent commit history\n"
                . "  composer install           - Install production dependencies\n"
                . "  php artisan migrate        - Run database migrations\n"
                . "  php artisan optimize       - Cache config, routes, and views\n"
                . "  php artisan health-check   - Run server diagnostics\n"
                . "  chmod -R 775 storage       - Fix Laravel writable permissions\n"
                . "  sudo systemctl reload nginx- Gracefully reload Nginx web server\n"
                . "  sudo systemctl restart php - Restart PHP-FPM service\n"
                . "  df -h / free -m / uptime   - System telemetry inspection\n"
                . "  clear                      - Reset the terminal screen\n";
        } elseif (str_starts_with($cmd, 'ssh ')) {
            $target = substr($rawCommand, 4);
            $output = "The authenticity of host '{$target}' can't be established.\n"
                . "ED25519 key fingerprint is SHA256:X9h8A2kL0P3qRtYwZ5mNv7bCx1Ds4Fg.\n"
                . "Warning: Permanently added '{$target}' (ED25519) to the list of known hosts.\n"
                . "Authenticated with public key: id_ed25519 (GitHub CI/CD Deployer)\n"
                . "Welcome to Ubuntu 24.04 LTS (GNU/Linux 6.8.0-31-generic x86_64)\n"
                . " * System load:  0.24               * Memory usage: 38% of 4.00GB\n"
                . " * IPv4 address: 192.168.1.100       * Storage:      14.2GB / 50.0GB (28%)\n"
                . "Last login: " . now()->subHours(3)->format('D M d H:i:s Y') . " from 103.21.244.0\n";
        } elseif (str_starts_with($cmd, 'git pull')) {
            $hash = substr(md5(uniqid()), 0, 7);
            $output = "remote: Enumerating objects: 12, done.\n"
                . "remote: Counting objects: 100% (12/12), done.\n"
                . "remote: Compressing objects: 100% (8/8), done.\n"
                . "remote: Total 10 (delta 4), reused 8 (delta 2), pack-reused 0\n"
                . "Unpacking objects: 100% (10/10), 4.12 KiB | 842.00 KiB/s, done.\n"
                . "From https://github.com/Excelsior-Technologies-Community/PHP_Laravel12_Deploye_To_Server\n"
                . " * branch            main       -> FETCH_HEAD\n"
                . "Updating 2f1a8c9..{$hash}\n"
                . "Fast-forward\n"
                . " app/Http/Controllers/ServerController.php | 28 ++++++++++++++++++++++++\n"
                . " routes/web.php                            |  8 +++++++\n"
                . " 2 files changed, 36 insertions(+)\n";
        } elseif ($cmd === 'git status') {
            $output = "On branch main\n"
                . "Your branch is up to date with 'origin/main'.\n\n"
                . "nothing to commit, working tree clean\n";
        } elseif (str_starts_with($cmd, 'git log')) {
            $output = "commit " . md5(uniqid()) . " (HEAD -> main, origin/main)\n"
                . "Author: DevOps Engineer <devops@excelsior.local>\n"
                . "Date:   " . now()->format('D M d H:i:s Y') . " +0530\n\n"
                . "    Release v1.2.0: Automated CI/CD deployment pipeline enhancements\n\n"
                . "commit b8c7d6e5f4a3b2c1d0e9f8a7b6c5d4e3f2a1b0c9\n"
                . "Author: Lead Developer <lead@excelsior.local>\n"
                . "Date:   " . now()->subDays(2)->format('D M d H:i:s Y') . " +0530\n\n"
                . "    feat: implement server health metrics and export csv functionality\n";
        } elseif (str_starts_with($cmd, 'composer install')) {
            $output = "Installing dependencies from lock file (including require-dev)\n"
                . "Verifying lock file contents...\n"
                . "Package operations: 0 installs, 1 update, 0 removals\n"
                . "  - Upgrading laravel/framework (v12.48.0 => v12.49.0): Extracting archive\n"
                . "Generating optimized autoload files containing 6,310 classes\n"
                . "> @php artisan package:discover --ansi\n"
                . "INFO Discovering packages.\n"
                . "  nesbot/carbon .......................................................................... DONE\n"
                . "  nunomaduro/termwind .................................................................... DONE\n"
                . "Package manifest generated successfully.\n";
        } elseif (str_starts_with($cmd, 'php artisan migrate')) {
            $output = "   INFO  Preparing database.\n\n"
                . "   INFO  Running migrations.\n\n"
                . "  2026_10_22_043126_add_duration_and_commit_to_deployments_table ................. 42.15ms DONE\n\n"
                . "Database is up to date.\n";
        } elseif (str_starts_with($cmd, 'php artisan optimize') || str_starts_with($cmd, 'php artisan config:cache')) {
            $output = "   INFO  Caching configuration.\n"
                . "  config ................................................................................. 12.4ms DONE\n\n"
                . "   INFO  Caching routes.\n"
                . "  routes ................................................................................. 18.2ms DONE\n\n"
                . "   INFO  Caching views.\n"
                . "  views .................................................................................. 24.8ms DONE\n\n"
                . "   INFO  Caching events.\n"
                . "  events .................................................................................  4.1ms DONE\n\n"
                . "Application cached successfully for optimal production throughput!\n";
        } elseif (str_starts_with($cmd, 'sudo systemctl reload nginx') || str_starts_with($cmd, 'systemctl reload nginx')) {
            $output = "Testing Nginx syntax before reload...\n"
                . "nginx: the configuration file /etc/nginx/nginx.conf syntax is ok\n"
                . "nginx: configuration file /etc/nginx/nginx.conf test is successful\n"
                . "[✓] Nginx worker processes reloaded without downtime.\n";
        } elseif (str_starts_with($cmd, 'sudo systemctl restart php') || str_starts_with($cmd, 'systemctl restart php')) {
            $output = "[✓] Stopping php8.2-fpm: php8.2-fpm.\n"
                . "[✓] Starting php8.2-fpm: php8.2-fpm.\n"
                . "PHP OPcache cleared and worker sockets active on unix:/run/php/php8.2-fpm.sock\n";
        } elseif (str_contains($cmd, 'chmod') && str_contains($cmd, 'storage')) {
            $output = "Updated permissions (775) on /var/www/PHP_Laravel12_Deploye_To_Server/storage\n"
                . "Updated permissions (775) on /var/www/PHP_Laravel12_Deploye_To_Server/bootstrap/cache\n"
                . "Read/write execution verified for user [www-data].\n";
        } elseif ($cmd === 'df -h') {
            $output = "Filesystem      Size  Used Avail Use% Mounted on\n"
                . "udev            1.9G     0  1.9G   0% /dev\n"
                . "tmpfs           392M  1.4M  391M   1% /run\n"
                . "/dev/sda1        49G   14G   33G  30% /\n"
                . "tmpfs           2.0G     0  2.0G   0% /dev/shm\n";
        } elseif ($cmd === 'free -m') {
            $output = "               total        used        free      shared  buff/cache   available\n"
                . "Mem:            3918        1420        1648          18         850        2498\n"
                . "Swap:           2048         120        1928\n";
        } elseif ($cmd === 'uptime') {
            $output = " " . now()->format('H:i:s') . " up 42 days, 14:22,  2 users,  load average: 0.14, 0.18, 0.12\n";
        } elseif ($cmd === 'whoami') {
            $output = "root\n";
        } elseif ($cmd === 'pwd') {
            $output = "/var/www/PHP_Laravel12_Deploye_To_Server\n";
        } elseif ($cmd === 'ls' || $cmd === 'ls -la') {
            $output = "total 64\n"
                . "drwxrwxr-x  11 www-data www-data  4096 Oct 22 09:30 .\n"
                . "drwxr-xr-x   4 root     root      4096 Jul 31 05:40 ..\n"
                . "-rw-rw-r--   1 www-data www-data   425 Oct 22 09:15 artisan\n"
                . "drwxrwxr-x   7 www-data www-data  4096 Oct 22 09:20 app\n"
                . "drwxrwxr-x   3 www-data www-data  4096 Oct 22 09:20 bootstrap\n"
                . "drwxrwxr-x   2 www-data www-data  4096 Oct 22 09:20 config\n"
                . "drwxrwxr-x   5 www-data www-data  4096 Oct 22 09:20 database\n"
                . "-rw-rw-r--   1 www-data www-data  1086 Oct 22 09:15 .env\n"
                . "drwxrwxr-x   5 www-data www-data  4096 Oct 22 09:20 public\n"
                . "drwxrwxr-x   6 www-data www-data  4096 Oct 22 09:20 resources\n"
                . "drwxrwxr-x   2 www-data www-data  4096 Oct 22 09:20 routes\n"
                . "drwxrwxrwx   5 www-data www-data  4096 Oct 22 09:20 storage\n"
                . "drwxrwxr-x  42 www-data www-data  4096 Oct 22 09:20 vendor\n";
        } else {
            $output = "bash: {$cmd}: command not found (type 'help' for available simulated commands)\n";
            $exitCode = 127;
        }

        return response()->json([
            'output' => $output,
            'exit_code' => $exitCode,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
