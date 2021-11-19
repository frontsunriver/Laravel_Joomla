<?php

namespace App\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class AweExtensionProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Addons';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */


    public function register()
    {
        $this->loadExtensions();

        $config_path = apply_filters('extension_config_path', []);
        if (!empty($config_path)) {
            foreach ($config_path as $path) {
                $this->mergeConfigFrom(
                    $path, 'extension'
                );
            }
        }
    }

    public function boot()
    {
        parent::boot();

        $config_path = apply_filters('extension_config_path', []);
        if (!empty($config_path)) {
            foreach ($config_path as $path) {
                $this->publishes([
                    $path => config_path('awebooking.php'),
                ]);
            }
        }

        $view_path = apply_filters('extension_view_path', []);
        if (!empty($view_path)) {
            foreach ($view_path as $name => $path) {
                $this->loadViewsFrom($path, $name);
            }
        }
    }

    public function map()
    {
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $route_web = apply_filters('extension_route_web', []);
        if (!empty($route_web)) {
            foreach ($route_web as $item) {
                Route::middleware('web')
                    ->namespace($this->namespace)
                    ->group($item);
            }
        }
    }

    protected function loadViewsFrom($path, $namespace)
    {
        $this->callAfterResolving('view', function ($view) use ($path, $namespace) {
            if (isset($this->app->config['view']['paths']) &&
                is_array($this->app->config['view']['paths'])) {
                foreach ($this->app->config['view']['paths'] as $viewPath) {
                    if (is_dir($appPath = $viewPath . '/vendor/' . $namespace)) {
                        $view->addNamespace($namespace, $appPath);
                    }
                }
            }

            $view->addNamespace($namespace, $path);
        });
    }

    public function loadExtensions()
    {
        global $hh_extensions;
        $path = app_path('Addons');
        $folders = glob($path . "/*");
        if (!empty($folders)) {
            foreach ($folders as $key => $folder) {
                if (is_dir($folder)) {
                    $folder_name = $this->_path_info($folder);
                    $file_path = $path . '/' . $folder_name . '/' . $folder_name . '.php';
                    if (is_file($file_path)) {
                        require_once($file_path);
                        $data = get_file_data($file_path);
                        $hh_extensions[$data['Slug']] = [
                            'Slug' => $data['Slug'],
                            'Name' => $data['Name'],
                            'Description' => $data['Description'],
                            'Author' => $data['Author'],
                            'Version' => $data['Version'],
                            'Tags' => $data['Tags']
                        ];
                    }
                }
            }
        }
    }

    private function _path_info($path = '', $return = '')
    {
        if ($return == 'dir') {
            $pathinfo = pathinfo($path);
            $result = $pathinfo['dirname'];
        } else {
            $pathinfo = pathinfo($path);
            $result = $pathinfo['basename'];
        }

        return $result;
    }


}
