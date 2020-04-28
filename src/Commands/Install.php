<?php


namespace Tsung\NovaUserManagement\Commands;


use Illuminate\Console\Command;

class Install extends Command
{
    protected $signature = "novauser:install {--force}";

    protected $description = "Install Nova User Management";

    public function handle()
    {
        $this->info('Replacing Default User Model');
        $this->replaceUserModel();
        $this->info("Done");

        $this->info('Replacing Default User Nova');
        $this->replaceUserNova();
        $this->info("Done");
    }

    private function replaceUserModel()
    {
        copy(__DIR__.'/../Stub/Models/User.stub', app_path('User.php'));
    }

    private function replaceUserNova()
    {
        copy(__DIR__.'/../Stub/Nova/User.stub', app_path('Nova/User.php'));
    }
}
