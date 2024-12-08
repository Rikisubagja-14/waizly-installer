<?php

namespace WaizlyInstaller\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WaizlyInstall extends Command
{
    protected $signature = 'waizly:install
                            {--skip-env-check : Skip the .env file check}
                            {--skip-admin-creation : Skip the admin account creation}';

    protected $description = 'Install the Waizly application and set up necessary files';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (!$this->option('skip-env-check') && !File::exists(base_path('.env'))) {
            $this->error('.env file is missing. Please create an .env file.');
            return 1;
        }

        // Add the default environment variables if missing
        $this->updateEnvFile();

        // Migrate database
        $this->info('Running migrations...');
        $this->call('migrate', ['--force' => true]);

        // Check if the admin user exists
        if (!$this->option('skip-admin-creation') && !$this->adminExists()) {
            $this->info('Creating admin user...');
            $this->createAdminUser();
        }

        $this->info('Waizly application installed successfully!');
        return 0;
    }

    protected function updateEnvFile()
    {
        $env = base_path('.env');
        if (!File::exists($env)) {
            $this->error('.env file is missing!');
            return;
        }

        $envContent = File::get($env);
        
        if (strpos($envContent, 'WAIZLY_DB_HOST') === false) {
            File::append($env, "\nWAIZLY_DB_HOST=127.0.0.1\nWAIZLY_DB_PORT=3306\n");
            $this->info('Environment file updated with default database settings.');
        }
    }

    protected function adminExists()
    {
        return DB::table('users')->where('email', 'admin@waizly.com')->exists();
    }

    protected function createAdminUser()
    {
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@waizly.com',
            'password' => bcrypt('admin123'),  // Default password (make sure to hash)
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info('Admin user created successfully!');
    }
}
