<?php

namespace YusamHub\LaravelSmarty;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory;

/**
 * Class SmartyServiceProvider
 * @package YusamHub\LaravelSmarty
 */
class LaravelSmartyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Smarty', function (\Illuminate\Contracts\Foundation\Application $app) {

            return new LaravelSmartyFactory(
                $app['view.engine.resolver'],
                $app['view.finder'],
                $app['events'],
                $this->getPackageConfig()
            );

        });
    }

    /**
     * @return array
     */
    private function getPackageConfig()
    {
        $configSmartyFile = base_path('/config/view.php');
        return (array) include($configSmartyFile);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->getAppView()->addExtension('tpl', 'smarty', function () {

            $smarty = $this->getSmartyView();

            return new LaravelSmartyViewEngine($smarty->getSmarty());
        });

        $view_composers = config("view.smarty.view.composers", []);

        foreach($view_composers as $views => $callbacks) {
            foreach($callbacks as $callback) {
                \Illuminate\Support\Facades\View::composer(
                    $views, $callback
                );
            }
        }
    }

    /**
     * @return Factory
     */
    protected function getAppView()
    {
        return $this->app['view'];
    }

    /**
     * @return LaravelSmartyFactory
     * @throws BindingResolutionException
     */
    protected function getSmartyView()
    {
        return $this->app->make('Smarty');
    }

}
