<?php

namespace WaizlyInstaller;

use Illuminate\Support\ServiceProvider;

class WaizlyInstallerServiceProvider extends ServiceProvider
{
    /**
     * Daftarkan perintah untuk package ini.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            \WaizlyInstaller\Console\WaizlyInstall::class,  // Mendaftarkan command
        ]);
    }

    /**
     * Booting layanan untuk package ini.
     *
     * @return void
     */
    public function boot()
    {
        // Bisa menambahkan file konfigurasi, migrasi, atau routing di sini.
    }
}
