<?php


namespace Tsung\NovaUserManagement\Commands;


use Illuminate\Console\Command;

class Install extends Command
{
    protected $signature = "novauser:install {--force}";

    protected $description = "Install Nova User Management";

    public function handle()
    {
        $this->replaceUserModel();

        $this->replaceUserNova();

        $this->publishConfig();

        $this->patchingNovaServiceProviderGate();

        $this->patchingAuthServiceProvider();
    }

    private function replaceUserModel()
    {
        $laravelVersion = explode('.', app()->version());
        $this->info('Replacing Default User Model');
        if ($laravelVersion[0] == "8") {
            copy(__DIR__.'/../Stub/Models/User8.php', app_path('Models/User.php'));
            $this->info("Done");
        } else {
            copy(__DIR__.'/../Stub/Models/User7.php', app_path('User.php'));
            $this->info("Done");
        }
    }

    private function replaceUserNova()
    {
        $laravelVersion = explode('.', app()->version());
        $this->info('Replacing Default User Nova');
        if ($laravelVersion[0] == "8") {
            copy(__DIR__.'/../Stub/Nova/User8.php', app_path('Nova/User.php'));
            $this->info("Done");
        } else {
            copy(__DIR__.'/../Stub/Nova/User7.php', app_path('Nova/User.php'));
            $this->info("Done");
        }
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

    private function patchingAuthServiceProvider()
    {
        $authServiceProviderPath = app_path('Providers/AuthServiceProvider.php');

        $this->info('Patching AuthServiceProvider to add Passport');
        $authServiceProviderContent = file_get_contents($authServiceProviderPath);
        $authServiceProviderContent = str_replace(
            '$this->registerPolicies();',
            '$this->registerPolicies();
        \Laravel\Passport\Passport::routes();
        \Laravel\Passport\Passport::tokensExpireIn(now()->addMinutes(60));
        \Laravel\Passport\Passport::refreshTokensExpireIn(now()->addHours(3));',
            $authServiceProviderContent
        );
        file_put_contents($authServiceProviderPath, $authServiceProviderContent);
        $this->info("Done");
    }
}
