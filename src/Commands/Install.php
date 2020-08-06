<?php


namespace Tsung\NovaUserManagement\Commands;


use Illuminate\Console\Command;

class Install extends Command
{
    protected $signature = "novauser:install {--force}";

    protected $description = "Install Nova User Management";

    public function handle()
    {
        $this->novaweb();

        $this->replaceUserModel();

        $this->replaceUserNova();

        $this->publishConfig();

        $this->patchingNovaServiceProviderGate();
    }

    private function novaweb()
    {
        $this->pInfo("Publishing Novaweb Assets");
        $this->call('vendor:publish', ['--tag' => 'novaweb-assets', '--force' => true]);
        $this->pInfo("Done Publishing Novaweb Assets");

        $this->pInfo("Publishing Novaweb welcome");
        $this->call('vendor:publish', ['--tag' => 'novaweb-welcome', '--force' => true]);
        $this->pInfo("Done Publishing Novaweb Welcome");
    }

    private function replaceUserModel()
    {
        $this->info('Replacing Default User Model');
        copy(__DIR__.'/../Stub/Models/User.stub', app_path('User.php'));
        $this->info("Done");
    }

    private function replaceUserNova()
    {
        $this->info('Replacing Default User Nova');
        copy(__DIR__.'/../Stub/Nova/User.stub', app_path('Nova/User.php'));
        $this->info("Done");
    }

    private function publishConfig()
    {
        $this->info("Publish Novauser Config");
        $this->call('vendor:publish', ['--tag' => 'novauser-config']);
        $this->info('Done');
    }

    private function patchingNovaServiceProviderGate()
    {
        $novaServiceProviderPath = app_path('Providers/NovaServiceProvider.php');

        $this->info("Patching NovaServiceProvider gate method");
        $gate_regex = "/in_array[\(\$\w\-\>\,\[\s\/]+.+/";
        $patchGate = '$user->hasPermissionTo(\'viewNova\');';
        $novaServiceProviderContent = file_get_contents($novaServiceProviderPath);
        $novaServiceProviderContent = preg_replace($gate_regex, $patchGate, $novaServiceProviderContent);
        file_put_contents($novaServiceProviderPath, $novaServiceProviderContent);
        $this->info("Done");
    }
}
