<?php

namespace Database\Seeders;

use App\Models\Deployment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeploymentSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        Deployment::query()->delete();

        $projects = [
            'PHP_Laravel12_Deploye_To_Server',
            'Ecommerce_API_Gateway',
            'Customer_Portal_V2',
            'Authentication_SSO_Service',
        ];

        $servers = [
            'Ubuntu VPS 24.04 (Primary Production)',
            'AWS EC2 Staging (ap-south-1)',
            'DigitalOcean Droplet (Bangalore)',
            'GCP Compute Engine (us-central1)',
        ];

        $environments = ['Production', 'Staging', 'Development'];
        $deployers = ['DevOps Bot', 'Alex Johnson', 'Maria Garcia', 'Liam Chen', 'GitHub Actions CI'];
        $remarksList = [
            'Automated CI/CD tag release deployment',
            'Hotfix for database connection timeout',
            'Scheduled release with compiled Vite assets',
            'Dependency version upgrade (Composer & NPM)',
            'Infrastructure migration and cache refresh',
            'Zero-downtime rollback verification',
            'Staging pre-flight deployment tests',
        ];

        // Seed 42 deployments spread across the last 30 days
        for ($i = 40; $i >= 0; $i--) {
            $date = now()->subDays(floor($i * 0.75))->subHours(rand(1, 14))->subMinutes(rand(1, 55));
            $env = $environments[array_rand($environments)];
            $isFail = (rand(1, 100) <= 12); // ~12% failure rate for realism
            $status = $isFail ? 'Failed' : 'Success';

            Deployment::create([
                'project_name' => $projects[array_rand($projects)],
                'server_name' => $servers[array_rand($servers)],
                'environment' => $env,
                'status' => $status,
                'duration_seconds' => rand(22, 115),
                'commit_hash' => substr(md5(uniqid()), 0, 7),
                'deployed_by' => $deployers[array_rand($deployers)],
                'deployment_date' => $date,
                'remarks' => $remarksList[array_rand($remarksList)] . ($isFail ? ' (failed on database lock)' : ''),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
