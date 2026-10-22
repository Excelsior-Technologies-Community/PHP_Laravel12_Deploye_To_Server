<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CiCdBuilderController extends Controller
{
    /**
     * Display the GitHub Actions CI/CD Workflow Builder
     */
    public function index()
    {
        $existingYaml = '';
        $workflowPath = base_path('.github/workflows/ci.yml');
        if (File::exists($workflowPath)) {
            $existingYaml = File::get($workflowPath);
        }

        return view('cicd-builder', compact('existingYaml'));
    }

    /**
     * Generate YAML content based on configuration options
     */
    public function generate(Request $request)
    {
        $yaml = $this->buildYamlContent($request->all());

        return response()->json([
            'success' => true,
            'yaml' => $yaml,
        ]);
    }

    /**
     * Download Generated ci.yml file
     */
    public function download(Request $request)
    {
        $yaml = $this->buildYamlContent($request->all());

        return response($yaml, 200, [
            'Content-Type' => 'application/x-yaml',
            'Content-Disposition' => 'attachment; filename="ci.yml"',
        ]);
    }

    /**
     * Save Generated ci.yml directly to .github/workflows/ci.yml
     */
    public function save(Request $request)
    {
        $yaml = $this->buildYamlContent($request->all());

        $dir = base_path('.github/workflows');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put($dir . '/ci.yml', $yaml);

        return response()->json([
            'success' => true,
            'message' => 'Successfully saved to .github/workflows/ci.yml in your project repository!',
            'path' => '.github/workflows/ci.yml',
        ]);
    }

    /**
     * Helper to construct pristine GitHub Actions YAML
     */
    private function buildYamlContent(array $config): string
    {
        $workflowName = $config['workflow_name'] ?? 'Laravel 12 CI/CD Pipeline';
        $phpVersion = $config['php_version'] ?? '8.2';
        $triggerPushMain = isset($config['trigger_push_main']) && $config['trigger_push_main'] == '1';
        $triggerPushStaging = isset($config['trigger_push_staging']) && $config['trigger_push_staging'] == '1';
        $triggerPr = isset($config['trigger_pr']) && $config['trigger_pr'] == '1';

        $stepComposer = isset($config['step_composer']) && $config['step_composer'] == '1';
        $stepEnv = isset($config['step_env']) && $config['step_env'] == '1';
        $stepNpm = isset($config['step_npm']) && $config['step_npm'] == '1';
        $stepTests = isset($config['step_tests']) && $config['step_tests'] == '1';
        $stepDeploySsh = isset($config['step_deploy_ssh']) && $config['step_deploy_ssh'] == '1';

        $branches = [];
        if ($triggerPushMain) $branches[] = '"main"';
        if ($triggerPushStaging) $branches[] = '"staging"';
        if (empty($branches)) $branches[] = '"main"';

        $branchesStr = implode(', ', $branches);

        $yaml = "name: {$workflowName}\n\n";
        $yaml .= "on:\n";
        $yaml .= "  push:\n";
        $yaml .= "    branches: [ {$branchesStr} ]\n";
        if ($triggerPr) {
            $yaml .= "  pull_request:\n";
            $yaml .= "    branches: [ \"main\" ]\n";
        }

        $yaml .= "\njobs:\n";
        $yaml .= "  build-and-test:\n";
        $yaml .= "    name: Build & Automated QA\n";
        $yaml .= "    runs-on: ubuntu-latest\n";
        $yaml .= "\n";
        $yaml .= "    steps:\n";
        $yaml .= "      - name: 📥 Checkout Repository\n";
        $yaml .= "        uses: actions/checkout@v4\n\n";

        $yaml .= "      - name: 🐘 Setup PHP Environment\n";
        $yaml .= "        uses: shivammathur/setup-php@v2\n";
        $yaml .= "        with:\n";
        $yaml .= "          php-version: '{$phpVersion}'\n";
        $yaml .= "          extensions: mbstring, xml, ctype, iconv, intl, pdo, pdo_mysql, bcmath, redis\n";
        $yaml .= "          coverage: none\n\n";

        if ($stepComposer) {
            $yaml .= "      - name: 📦 Cache Composer Dependencies\n";
            $yaml .= "        uses: actions/cache@v4\n";
            $yaml .= "        with:\n";
            $yaml .= "          path: vendor\n";
            $yaml .= "          key: \${{ runner.os }}-composer-\${{ hashFiles('**/composer.lock') }}\n";
            $yaml .= "          restore-keys: \${{ runner.os }}-composer-\n\n";

            $yaml .= "      - name: ⚡ Install Composer Packages\n";
            $yaml .= "        run: composer install --no-interaction --prefer-dist --optimize-autoloader\n\n";
        }

        if ($stepEnv) {
            $yaml .= "      - name: 🔑 Configure Environment File & App Key\n";
            $yaml .= "        run: |\n";
            $yaml .= "          cp .env.example .env\n";
            $yaml .= "          php artisan key:generate\n\n";
        }

        if ($stepNpm) {
            $yaml .= "      - name: 🎨 Setup Node.js & Compile Vite Assets\n";
            $yaml .= "        uses: actions/setup-node@v4\n";
            $yaml .= "        with:\n";
            $yaml .= "          node-version: 20\n";
            $yaml .= "      - run: npm ci\n";
            $yaml .= "      - run: npm run build\n\n";
        }

        if ($stepTests) {
            $yaml .= "      - name: 🧪 Execute PHPUnit / Pest Test Suite\n";
            $yaml .= "        run: php artisan test --parallel\n\n";
        }

        if ($stepDeploySsh) {
            $yaml .= "  deploy-to-server:\n";
            $yaml .= "    name: 🚀 Deploy to Remote VPS Server\n";
            $yaml .= "    needs: build-and-test\n";
            $yaml .= "    if: github.ref == 'refs/heads/main'\n";
            $yaml .= "    runs-on: ubuntu-latest\n";
            $yaml .= "\n";
            $yaml .= "    steps:\n";
            $yaml .= "      - name: 📡 Execute SSH Deployment Commands\n";
            $yaml .= "        uses: appleboy/ssh-action@v1.0.3\n";
            $yaml .= "        with:\n";
            $yaml .= "          host: \${{ secrets.SERVER_HOST }}\n";
            $yaml .= "          username: \${{ secrets.SERVER_USERNAME }}\n";
            $yaml .= "          key: \${{ secrets.SERVER_SSH_KEY }}\n";
            $yaml .= "          port: 22\n";
            $yaml .= "          script: |\n";
            $yaml .= "            cd /var/www/PHP_Laravel12_Deploye_To_Server\n";
            $yaml .= "            git pull origin main\n";
            $yaml .= "            composer install --no-dev --optimize-autoloader --no-interaction\n";
            $yaml .= "            php artisan migrate --force\n";
            $yaml .= "            php artisan config:cache\n";
            $yaml .= "            php artisan route:cache\n";
            $yaml .= "            php artisan view:cache\n";
            $yaml .= "            sudo systemctl reload php8.2-fpm\n";
            $yaml .= "            sudo systemctl reload nginx\n";
            $yaml .= "            echo \"Deployment successfully finished at \$(date)\"\n";
        }

        return $yaml;
    }
}
