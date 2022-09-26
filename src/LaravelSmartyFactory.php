<?php

namespace YusamHub\LaravelSmarty;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\ViewFinderInterface;
use ReflectionClass;
use SmartyException;

/**
 * Class SmartyFactory
 * @package YusamHub\LaravelSmarty
 */
class LaravelSmartyFactory extends Factory
{
    /**
     * @var LaravelSmarty
     */
    protected LaravelSmarty $smarty;

    /**
     * SmartyFactory constructor.
     * @param EngineResolver $engines
     * @param ViewFinderInterface $finder
     * @param Dispatcher $events
     * @param array $config
     * @throws LaravelSmartyPropertyNotFoundException
     * @throws SmartyException
     */
    public function __construct(
        EngineResolver $engines,
        ViewFinderInterface $finder,
        Dispatcher $events,
        array $config = []
    ) {

        parent::__construct($engines, $finder, $events);

        $this->smarty = new LaravelSmarty();
        $this->smarty->setViewFactory($this);

        $this->smarty->addPluginsDir($config['pluginsDir'] ?? []);

        $this->smartyInit($config['smarty'] ?? []);
    }

    /**
     * @return LaravelSmarty
     */
    public function getSmarty()
    {
        return $this->smarty;
    }

    /**
     * @param array $config
     * @throws LaravelSmartyPropertyNotFoundException
     * @throws SmartyException
     */
    protected function smartyInit(array $config)
    {
        $this->smarty->registerPlugin("block","t", function($params, $value, $smarty)
        {
            if (is_null($value)){
                $value = "";
            }
            if (is_array($params)){
                return __($value, $params);
            } else {
                return __($value);
            }
        });

        $this->smarty->registerPlugin("modifier","tm", function($value)
        {
            if (is_null($value)){
                $value = "";
            }
            return __($value);
        });

        $this->smarty->registerPlugin("function","tf", function($params)
        {
            $values = array_values($params);
            if (!isset($values[0])) {
                return '';
            }
            return __($values[0]);
        });

        if (!isset($config['debugging'])) {
            $config['debugging'] = true;
        }
        if (!isset($config['compile_check'])) {
            $config['compile_check'] = true;
        }
        if (!isset($config['caching'])) {
            $config['caching'] = false;
        }
        if (!isset($config['cache_lifetime'])) {
            $config['cache_lifetime'] = 120;
        }
        if (!isset($config['template_dir'])) {
            $config['template_dir'] = realpath(resource_path('views'));
        }
        if (!isset($config['config_dir'])) {
            $config['config_dir'] = realpath(resource_path('views'));
        }
        if (!isset($config['compile_dir'])) {
            $config['compile_dir'] = realpath(storage_path('framework/views'));
        }
        if (!isset($config['cache_dir'])) {
            $config['cache_dir'] = realpath(storage_path('framework/cache'));
        }

        foreach($config as $key => $val)
        {
            $reflectionClass = new ReflectionClass($this->smarty);

            if (!$reflectionClass->hasProperty($key))
            {
                throw new LaravelSmartyPropertyNotFoundException("{$key} : Property not found in [".get_class($this->smarty)."]");
            }

            $this->smarty->$key = $val;
        }
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws LaravelSmartyMethodNotFoundException
     */
    public function __call($method, $parameters)
    {
        $reflectionClass = new ReflectionClass($this->smarty);

        if (!$reflectionClass->hasMethod($method)) {
            throw new LaravelSmartyMethodNotFoundException("{$method} : Method not found in  [".get_class($this->smarty)."]");
        }

        return call_user_func_array([$this->smarty, $method], $parameters);
    }
}
