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
    protected $smarty;

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
        $this->smarty->addPluginsDir(isset($config['smarty']['pluginsDir']) ? $config['smarty']['pluginsDir'] : []);
        $this->smartyInit(isset($config['smarty']['smarty']) ? $config['smarty']['smarty'] : []);
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
     * @throws \ReflectionException
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
