<?php

namespace Bocanhcam\NovaBreadcrumb\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class ReplaceBreadcrumbsComponent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova:breadcrumbs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Nova Breadcrumbs';

    /**
     * @var string
     */
    protected string $novaPath;
    /**
     * @var Filesystem
     */
    protected Filesystem $novaStorage;

    /**
     * @return int
     */
    public function handle(): int
    {
        $this->novaPath = base_path('vendor/laravel/nova');

        $this->novaStorage = Storage::build([
            'driver' => 'local',
            'root'   => $this->novaPath,
            'throw'  => false,
        ]);

        $this->replaceBreadcrumbsComponent();

        return 0;
    }

    /**
     * @return void
     */
    public function replaceBreadcrumbsComponent(): void
    {
        $this->info('Starting the installation and build process. This may take a few minutes.');

        $this->novaStorage->put(
            'resources/js/components/Menu/Breadcrumbs.vue',
            file_get_contents(__DIR__ . '/../../../resources/js/components/Menu/Breadcrumbs.vue'));

        if (!$this->novaStorage->exists('postcss.config.js')) {
            $this->novaStorage->put('postcss.config.js',
            file_get_contents(__DIR__ . '/../../../postcss.config.js'));
        }

        if ($this->novaStorage->exists('webpack.mix.js.dist')) {
            $content = $this->novaStorage->get('webpack.mix.js.dist');
            $this->novaStorage->put('webpack.mix.js', $content);
        }

        $this->info('Running "npm install" to fetch dependencies. Please wait...');
        $install = Process::run('cd '. $this->novaPath. ' && npm install');
        if ($install->failed()) {
            $this->error($install->errorOutput());
            return;
        }

        $this->info('Running "npm run production" to build the production assets. Please wait...');
        $build = Process::run('cd '. $this->novaPath. ' && npm run production');
        if ($build->failed()) {
            $this->error($build->errorOutput());
            return;
        }

        $this->call('vendor:publish', [
            '--tag' => 'nova-assets',
            '--force' => true,
        ]);
    }
}
