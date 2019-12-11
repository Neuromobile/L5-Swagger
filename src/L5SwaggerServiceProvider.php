<?php namespace Darkaonline\L5Swagger;

use Darkaonline\L5Swagger\Console\GenerateDocsCommand;
use Darkaonline\L5Swagger\Console\PublishAssetsCommand;
use Darkaonline\L5Swagger\Console\PublishCommand;
use Darkaonline\L5Swagger\Console\PublishConfigCommand;
use Darkaonline\L5Swagger\Console\PublishViewsCommand;
use Illuminate\Support\ServiceProvider;

class L5SwaggerServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;


    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $viewPath = __DIR__.'/../resources/views';
        $this->loadViewsFrom($viewPath, 'l5-swagger');

        // Publish a config file
        $configPath = __DIR__ . '/../config/l5-swagger.php';
        $this->publishes([
            $configPath => config_path('l5-swagger.php')
        ], 'config');

        //Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/l5-swagger'),
        ],'views');

        //Publish assets
        $this->publishes([
            __DIR__.'/../resources/assets' => base_path('public/vendor/l5-swagger'),
        ],'assets');

        //Include routes
        include __DIR__.'/routes.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/l5-swagger.php';
        $this->mergeConfigFrom($configPath, 'l5-swagger');

	$this->app->singleton('command.l5-swagger.publish', function () {
            return new PublishCommand();
	});

        $this->app->singleton('command.l5-swagger.publish-config', function () {
            return new PublishConfigCommand();
	});

        $this->app->singleton('command.l5-swagger.publish-views', function () {
            return new PublishViewsCommand();
	});

        $this->app->singleton('command.l5-swagger.publish-assets', function () {
            return new PublishAssetsCommand();
	});

        $this->app->singleton('command.l5-swagger.generate', function () {
            return new GenerateDocsCommand();
        });

        $this->commands(
            'command.l5-swagger.publish',
            'command.l5-swagger.publish-config',
            'command.l5-swagger.publish-views',
            'command.l5-swagger.publish-assets',
            'command.l5-swagger.generate'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.l5-swagger.publish',
            'command.l5-swagger.publish-config',
            'command.l5-swagger.publish-views',
            'command.l5-swagger.publish-assets',
            'command.l5-swagger.generate'
        ];
    }

}
