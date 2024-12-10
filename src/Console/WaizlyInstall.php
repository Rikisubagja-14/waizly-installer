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


}
