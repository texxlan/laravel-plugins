<?php
namespace Oneso\LaravelPlugins;

use Illuminate\Contracts\Foundation\Application;

abstract class Plugin
{
    protected $app;

    /**
     * The Plugin Name.
     *
     * @var string
     */
    public $name;

    /**
     * A description of the plugin.
     * 
     * @var string
     */
    public $description;

    /**
     * The version of the plugin.
     * 
     * @var string
     */
    public $version;

    /**
     * @var $this
     */
    private $reflector = null;

    /**
     * Plugin constructor.
     *
     * @param $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->checkPluginName();
    }

    abstract public function boot();

    /**
     * Check for empty plugin name.
     *
     * @throws \InvalidArgumentException
     */
    private function checkPluginName()
    {
        if (!$this->name) {
            throw new \InvalidArgumentException('Missing Plugin name.');
        }
    }

    /**
     * Returns the view namespace in a camel case format based off
     * the plugins class name, with plugin stripped off the end.
     * 
     * Eg: ArticlesPlugin will be accessible through 'plugin:articles::<view name>'
     *
     * @return string
     */
    protected function getViewNamespace()
    {
        return 'plugin:' . camel_case(
            mb_substr(
                get_called_class(),
                strrpos(get_called_class(), '\\') + 1,
                -6
            )
        );
    }

    /**
     * Add a view namespace for this plugin.
     * Eg: view("plugin:articles::{view_name}")
     *
     * @param string $path
     */
    protected function enableViews($path = 'views')
    {
        $this->app['view']->addNamespace(
            $this->getViewNamespace(),
            $this->getPluginPath() . DIRECTORY_SEPARATOR . $path
        );
    }

    /**
     * Enable routes for this plugin.
     *
     * @param string $path
     */
    protected function enableRoutes($path = 'routes.php')
    {
        $this->app->router->group(['namespace' => $this->getPluginControllerNamespace()], function ($app) use ($path) {
            require $this->getPluginPath() . DIRECTORY_SEPARATOR . $path;
        });
    }

    /**
     * Register a database migration path for this plugin.
     *
     * @param  array|string  $paths
     * @return void
     */
    protected function enableMigrations($paths = 'migrations')
    {
        $this->app->afterResolving('migrator', function ($migrator) use ($paths) {
            foreach ((array) $paths as $path) {
                $migrator->path($this->getPluginPath() . DIRECTORY_SEPARATOR . $path);
            }
        });
    }

    /**
     * @return string
     */
    public function getPluginPath()
    {
        $reflector = $this->getReflector();
        $fileName  = $reflector->getFileName();

        return dirname($fileName);
    }

    /**
     * @return string
     */
    protected function getPluginControllerNamespace()
    {
        $reflector = $this->getReflector();
        $baseDir   = str_replace($reflector->getShortName(), '', $reflector->getName());

        return $baseDir . 'Http\\Controllers';
    }

    /**
     * @return \ReflectionClass
     */
    private function getReflector()
    {
        if (is_null($this->reflector)) {
            $this->reflector = new \ReflectionClass($this);
        }

        return $this->reflector;
    }

    /**
     * Returns a plugin view
     *
     * @param $view
     * @return \Illuminate\View\View
     */
    protected function view($view)
    {
        return view($this->getViewNamespace() . '::' . $view);
    }
}
