<?php

namespace MsCart\Acl;

use Illuminate\Support\ServiceProvider;



class AclServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
         $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'acl');
         $this->loadViewsFrom(__DIR__.'/../resources/views', 'acl');
        //$this->loadMigrationsFrom(__DIR__.'/../database/migrations');
         $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }



    // dump(config('settings.admin_language'));
        //get the current admin language from database
         \App::setLocale(\Setting::get('admin_language'));


        $menu =  \Menu::get('Sidebar');
        $acl = $menu->add(__('acl::acl.name'),    ['segment2'=>'acl', 'icon'=> 'icon-key '])->nickname('acl')->data('order', 1);
        $menu->acl->add(__('acl::acl.add_roles'),config('app.admin_prefix').'/acl/add-roles');
        $menu->acl->add(__('acl::acl.list_roles'),config('app.admin_prefix').'/acl/list-roles');




    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/acl.php', 'acl');

        // Register the service the package provides.
        $this->app->singleton('acl', function ($app) {
            return new Acl;
        });



    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['acl'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/acl.php' => config_path('acl.php'),
        ], 'acl.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/mscart'),
        ], 'acl.views');*/

        // Publishing assets.
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('backend/vendor/mscart/acl/assets'),
        ], 'acl.views');

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/mscart'),
        ], 'acl.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
