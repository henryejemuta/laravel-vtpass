<?php
/**
 * Created By: Henry Ejemuta
 * Project: laravel-vTPass
 * Class Name: InstallLaravelVTPass.php
 * Date Created: 7/13/20
 * Time Created: 7:26 PM
 */

namespace HenryEjemuta\LaravelVTPass\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallLaravelVTPass extends Command
{
    protected $signature = 'vtpass:init';

    protected $description = 'Install Laravel VTPass package';

    public function handle()
    {
        $this->info('Installing Laravel VTPass by Henry Ejemuta...');

        $this->info('Publishing configuration...');

        $this->call('vendor:publish', [
            '--provider' => "HenryEjemuta\LaravelVTPass\LaravelVTPassServiceProvider",
            '--tag' => "config"
        ]);

        $this->info('Configuration Published!');

        $this->info('Checking for environmental variable file (.env)');
        if (file_exists($path = $this->envPath()) === false) {
            $this->info('Environmental variable file (.env) not found!');
        } else {
            if ($this->isConfirmed() === false) {
                $this->comment('Phew... No changes were made to your .env file');
                return;
            }
            $this->info('Now writing .env file with VTPass Sandbox credential for you to modify...');

            $this->writeChanges($path, "VTPASS_BASE_URL", "base_url", 'https://sandbox.vtpass.com/api');
            $this->writeChanges($path, "VTPASS_USERNAME", "username", "henry.ejemuta@gmail.com'");
            $this->writeChanges($path, "VTPASS_PASSWORD", "password", "sandbox");

        }

        $this->info('Laravel VTPass Package Installation Complete!');
    }

    private function writeChanges($path, string $key, string $configKey, $value){
        if (Str::contains(file_get_contents($path), "$key") === false) {
            $this->info("Now writing .env with $key=$value ...");
            file_put_contents($path, PHP_EOL."$key=$value".PHP_EOL, FILE_APPEND);
        }else{
            $this->info("Now updating $key value in your .env to $value ...");
            // update existing entry
            file_put_contents($path, str_replace(
                "$key=".$this->laravel['config']["vTPass.$configKey"],
                "$key=$value", file_get_contents($path)
            ));
        }
    }


    /**
     * Get the .env file path.
     *
     * @return string
     */
    protected function envPath()
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        // check if laravel version Less than 5.4.17
        if (version_compare($this->laravel->version(), '5.4.17', '<')) {
            return $this->laravel->basePath() . DIRECTORY_SEPARATOR . '.env';
        }

        return $this->laravel->basePath('.env');
    }

    /**
     * Check if the modification is confirmed.
     *
     * @return bool
     */
    protected function isConfirmed()
    {
        return $this->confirm(
            'If your VTPass details are set within your .env file they would be overridden. Are you sure you want to override them if exists?'
        );
    }
}
